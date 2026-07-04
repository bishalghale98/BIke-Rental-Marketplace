<?php

namespace App\Http\Controllers\Company;

use App\Enums\BookingStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Notifications\BookingCancelledNotification;
use App\Notifications\BookingCompletedNotification;
use App\Notifications\BookingConfirmedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(): View
    {
        $company = Auth::user()->company;
        $bikeIds = $company->bikes()->pluck('id');

        $bookings = Booking::whereIn('bike_id', $bikeIds)
            ->with('bike', 'customer.user')
            ->latest()
            ->paginate(15);

        return view('company.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking): View
    {
        $company = Auth::user()->company;

        abort_if($booking->bike->company_id !== $company->id, 403);

        $booking->load('bike.category', 'bike.images', 'customer.user');

        return view('company.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        $company = Auth::user()->company;

        abort_if($booking->bike->company_id !== $company->id, 403);

        $request->validate([
            'status' => ['required', 'string', 'in:confirmed,ongoing,completed,cancelled'],
        ]);

        $newStatus = $request->status;
        $currentStatus = $booking->status->value;

        $allowed = match ($currentStatus) {
            'pending' => in_array($newStatus, ['confirmed', 'cancelled']),
            'confirmed' => in_array($newStatus, ['ongoing', 'cancelled']),
            'ongoing' => $newStatus === 'completed',
            default => false,
        };

        if (!$allowed) {
            return back()->with('error', 'Invalid status transition.');
        }

        $data = ['status' => BookingStatusEnum::from($newStatus)];

        if ($newStatus === 'cancelled') {
            $data['cancellation_reason'] = $request->cancellation_reason ?? 'Cancelled by company';
            $data['cancelled_by'] = 'company';
            $data['cancelled_at'] = now();

            $hoursBeforeStart = now()->diffInHours($booking->start_date, false);
            $data['refund_amount'] = match (true) {
                $hoursBeforeStart >= 24 => $booking->total_amount,
                $hoursBeforeStart >= 0 => $booking->total_amount * 0.5,
                default => 0,
            };
        }

        if ($newStatus === 'completed') {
            $effectiveEnd = $booking->extended_until ?? $booking->end_date;
            if (now()->gt($effectiveEnd)) {
                $hoursLate = $effectiveEnd->diffInHours(now());
                $hourlyRate = $booking->hourly_price ?: ($booking->daily_price / 24);
                $lateFee = min($hoursLate * $hourlyRate, $booking->daily_price * 3);
                $data['late_fee'] = round($lateFee, 2);
            }
        }

        $booking->update($data);

        $customer = $booking->customer->user;

        match ($newStatus) {
            'confirmed' => $customer->notify(new BookingConfirmedNotification($booking)),
            'completed' => $customer->notify(new BookingCompletedNotification($booking)),
            'cancelled' => $customer->notify(new BookingCancelledNotification($booking, 'company')),
            default => null,
        };

        $message = 'Booking status updated.';
        if ($newStatus === 'completed' && $booking->late_fee) {
            $message .= ' Late fee incurred: $' . number_format($booking->late_fee, 2) . '.';
        }

        return redirect()->route('company.bookings.show', $booking)
            ->with('success', $message);
    }
}
