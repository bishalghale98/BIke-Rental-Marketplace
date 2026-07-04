<?php

namespace App\Http\Controllers\Customer;

use App\Enums\BookingStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Bike;
use App\Models\Booking;
use App\Models\CustomerProfile;
use App\Notifications\BookingCancelledNotification;
use App\Notifications\BookingCreatedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookingController extends Controller
{
    private function getCustomer(): ?CustomerProfile
    {
        return Auth::user()->customerProfile;
    }

    public function index(): View|RedirectResponse
    {
        $customer = $this->getCustomer();

        if (!$customer) {
            return redirect()->route('customer.dashboard');
        }

        $bookings = Booking::where('customer_id', $customer->id)
            ->with('bike.category', 'bike.images', 'review')
            ->latest()
            ->paginate(10);

        return view('customer.bookings.index', compact('bookings'));
    }

    public function create(Bike $bike): View
    {
        abort_if($bike->status !== 'active' || !$bike->is_available, 404);

        $bike->load('images', 'category', 'company');

        return view('customer.bookings.create', compact('bike'));
    }

    public function store(Request $request, Bike $bike): RedirectResponse
    {
        abort_if($bike->status !== 'active' || !$bike->is_available, 404);

        $request->validate([
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after:start_date'],
        ]);

        $start = $request->date('start_date');
        $end = $request->date('end_date');

        $overlap = Booking::where('bike_id', $bike->id)
            ->whereIn('status', [BookingStatusEnum::Confirmed->value, BookingStatusEnum::Ongoing->value])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                  ->orWhereBetween('end_date', [$start, $end])
                  ->orWhere(function ($q) use ($start, $end) {
                      $q->where('start_date', '<=', $start)
                        ->where('end_date', '>=', $end);
                  });
            })
            ->exists();

        if ($overlap) {
            return back()->withErrors(['dates' => 'The bike is not available for the selected dates.'])->withInput();
        }

        $totalHours = $start->diffInHours($end);
        $totalDays = $start->diffInDays($end) + 1;
        $totalWeeks = floor($totalDays / 7);
        $remainingDays = $totalDays % 7;

        $hourlyPrice = $bike->hourly_price;
        $dailyPrice = $bike->daily_price;
        $weeklyPrice = $bike->weekly_price;

        $subtotal = 0;
        $totalHoursCalc = 0;
        $totalDaysCalc = 0;
        $totalWeeksCalc = 0;

        if ($weeklyPrice && $totalWeeks > 0) {
            $totalWeeksCalc = $totalWeeks;
            $subtotal += $totalWeeks * $weeklyPrice;
        }

        if ($dailyPrice && $remainingDays > 0) {
            $totalDaysCalc = $remainingDays;
            $subtotal += $remainingDays * $dailyPrice;
        } elseif ($dailyPrice && $totalWeeks === 0 && $totalHours < 24) {
            $totalDaysCalc = 1;
            $subtotal += $dailyPrice;
        }

        if ($hourlyPrice && $subtotal === 0 && $totalHours < 24) {
            $totalHoursCalc = $totalHours;
            $subtotal += $totalHours * $hourlyPrice;
        }

        $customer = $this->getCustomer();

        if (!$customer) {
            return redirect()->route('customer.dashboard');
        }

        $bookingNumber = 'BK-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

        $booking = Booking::create([
            'booking_number' => $bookingNumber,
            'customer_id' => $customer->id,
            'bike_id' => $bike->id,
            'start_date' => $start,
            'end_date' => $end,
            'hourly_price' => $hourlyPrice,
            'daily_price' => $dailyPrice,
            'weekly_price' => $weeklyPrice,
            'total_hours' => $totalHoursCalc,
            'total_days' => $totalDaysCalc,
            'total_weeks' => $totalWeeksCalc,
            'subtotal' => $subtotal,
            'total_amount' => $subtotal,
            'status' => BookingStatusEnum::Pending,
        ]);

        $bike->company->user->notify(new BookingCreatedNotification($booking));

        return redirect()->route('customer.bookings.show', $booking)
            ->with('success', 'Booking created successfully.');
    }

    public function show(Booking $booking): View|RedirectResponse
    {
        $customer = $this->getCustomer();

        if (!$customer) {
            return redirect()->route('customer.dashboard');
        }

        abort_if($booking->customer_id !== $customer->id, 403);

        $booking->load('bike.category', 'bike.images', 'bike.company');

        return view('customer.bookings.show', compact('booking'));
    }

    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        $customer = $this->getCustomer();

        if (!$customer) {
            return redirect()->route('customer.dashboard');
        }

        abort_if($booking->customer_id !== $customer->id, 403);

        if (!in_array($booking->status->value, ['pending', 'confirmed'])) {
            return back()->with('error', 'This booking cannot be cancelled.');
        }

        $request->validate(['cancellation_reason' => ['nullable', 'string', 'max:1000']]);

        $hoursBeforeStart = now()->diffInHours($booking->start_date, false);

        $refundAmount = match (true) {
            $hoursBeforeStart >= 24 => $booking->total_amount,
            $hoursBeforeStart >= 0 => $booking->total_amount * 0.5,
            default => 0,
        };

        $booking->update([
            'status' => BookingStatusEnum::Cancelled,
            'cancellation_reason' => $request->cancellation_reason,
            'cancelled_by' => 'customer',
            'cancelled_at' => now(),
            'refund_amount' => $refundAmount,
        ]);

        $booking->bike->company->user->notify(new BookingCancelledNotification($booking, 'customer'));

        $message = 'Booking cancelled successfully.';
        if ($refundAmount > 0) {
            $message .= ' Estimated refund: $' . number_format($refundAmount, 2) . '.';
        }

        return redirect()->route('customer.bookings.show', $booking)
            ->with('success', $message);
    }
}
