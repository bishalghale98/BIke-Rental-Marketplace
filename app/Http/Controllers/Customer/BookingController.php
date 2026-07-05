<?php

namespace App\Http\Controllers\Customer;

use App\Enums\BookingStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Bike;
use App\Models\Booking;
use App\Models\CustomerProfile;
use App\Notifications\BookingCancelledNotification;
use App\Notifications\BookingCreatedNotification;
use App\Services\BookingPricingService;
use App\Services\PaymentService;
use App\Services\RefundService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        private BookingPricingService $pricingService,
        private PaymentService $paymentService,
        private RefundService $refundService,
    ) {}

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
            ->whereIn('status', [
                BookingStatusEnum::DepositPaid->value,
                BookingStatusEnum::Confirmed->value,
                BookingStatusEnum::PickedUp->value,
            ])
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

        $pricing = $this->pricingService->calculate($start, $end, $bike);

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
            'hourly_price' => $bike->hourly_price,
            'daily_price' => $bike->daily_price,
            'weekly_price' => $bike->weekly_price,
            'total_hours' => $pricing['total_hours'],
            'total_days' => $pricing['total_days'],
            'total_weeks' => $pricing['total_weeks'],
            'subtotal' => $pricing['subtotal'],
            'total_amount' => $pricing['total_amount'],
            'status' => BookingStatusEnum::PendingPayment,
        ]);

        $bike->company->user->notify(new BookingCreatedNotification($booking));

        return redirect()->route('customer.payment.checkout', $booking)
            ->with('success', 'Booking created. Please complete the deposit payment to confirm.');
    }

    public function show(Booking $booking): View|RedirectResponse
    {
        $customer = $this->getCustomer();

        if (!$customer) {
            return redirect()->route('customer.dashboard');
        }

        abort_if($booking->customer_id !== $customer->id, 403);

        $booking->load('bike.category', 'bike.images', 'bike.company', 'payments');

        return view('customer.bookings.show', compact('booking'));
    }

    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        $customer = $this->getCustomer();

        if (!$customer) {
            return redirect()->route('customer.dashboard');
        }

        abort_if($booking->customer_id !== $customer->id, 403);

        $cancellableStatuses = [
            BookingStatusEnum::PendingPayment->value,
            BookingStatusEnum::DepositPaid->value,
            BookingStatusEnum::Confirmed->value,
        ];

        if (!in_array($booking->status->value, $cancellableStatuses)) {
            return back()->with('error', 'This booking cannot be cancelled.');
        }

        $request->validate(['cancellation_reason' => ['nullable', 'string', 'max:1000']]);

        $refundAmount = $this->refundService->calculateRefundForCancellation($booking, 'customer');

        $booking->update([
            'status' => BookingStatusEnum::Cancelled,
            'cancellation_reason' => $request->cancellation_reason,
            'cancelled_by' => 'customer',
            'cancelled_at' => now(),
            'refund_amount' => $refundAmount,
        ]);

        $depositPayment = $booking->payments()
            ->where('type', 'deposit')
            ->where('status', 'completed')
            ->first();

        if ($depositPayment && $refundAmount > 0) {
            try {
                $this->paymentService->refund($depositPayment);
                $booking->update(['status' => BookingStatusEnum::Refunded]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Refund failed, manual processing needed.', [
                    'payment_id' => $depositPayment->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $booking->bike->company->user->notify(new BookingCancelledNotification($booking, 'customer'));

        $message = 'Booking cancelled successfully.';
        if ($refundAmount > 0) {
            $message .= ' NPR ' . number_format($refundAmount, 2) . ' will be refunded.';
        }

        return redirect()->route('customer.bookings.show', $booking)
            ->with('success', $message);
    }
}
