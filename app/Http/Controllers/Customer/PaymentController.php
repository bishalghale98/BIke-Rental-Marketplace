<?php

namespace App\Http\Controllers\Customer;

use App\Enums\BookingStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
    ) {}

    public function checkout(Booking $booking): View|RedirectResponse
    {
        $customer = Auth::user()->customerProfile;

        if (!$customer) {
            return redirect()->route('customer.dashboard');
        }

        abort_if($booking->customer_id !== $customer->id, 403);

        if ($booking->status->value !== 'pending_payment') {
            return redirect()->route('customer.bookings.show', $booking)
                ->with('info', 'This booking has already been processed.');
        }

        $booking->load('bike.category', 'bike.images', 'bike.company');

        return view('customer.payments.checkout', compact('booking'));
    }

    public function pay(Request $request, Booking $booking, string $gateway): View|RedirectResponse
    {
        $customer = Auth::user()->customerProfile;

        abort_if($booking->customer_id !== $customer->id, 403);
        abort_if($booking->status->value !== 'pending_payment', 403);
        abort_if(!in_array($gateway, ['khalti', 'esewa']), 400);

        try {
            $result = $this->paymentService->initiateDeposit($booking, $gateway);

            if ($gateway === 'khalti') {
                return redirect()->away($result['payment_url']);
            }

            return view('customer.payments.esewa-redirect', compact('result', 'booking'));
        } catch (\Exception $e) {
            return redirect()->route('customer.payment.checkout', $booking)
                ->with('error', 'Payment initiation failed: ' . $e->getMessage());
        }
    }

    public function callback(Request $request, string $gateway): RedirectResponse
    {
        try {
            $payment = $this->paymentService->verify($gateway, $request->all());
            $booking = $payment->booking;

            if ($payment->status === 'completed') {
                return redirect()->route('customer.payment.success', $booking)
                    ->with('success', 'Payment completed successfully.');
            }

            return redirect()->route('customer.payment.failure', $booking)
                ->with('error', 'Payment verification failed.');
        } catch (\Exception $e) {
            return redirect()->route('customer.bookings.index')
                ->with('error', 'Payment processing error: ' . $e->getMessage());
        }
    }

    public function success(Booking $booking): View|RedirectResponse
    {
        $customer = Auth::user()->customerProfile;

        abort_if($booking->customer_id !== $customer->id, 403);

        $booking->load('bike');

        return view('customer.payments.success', compact('booking'));
    }

    public function failure(Booking $booking): View|RedirectResponse
    {
        $customer = Auth::user()->customerProfile;

        abort_if($booking->customer_id !== $customer->id, 403);

        $booking->load('bike');

        return view('customer.payments.failure', compact('booking'));
    }

    public function payRemaining(Request $request, Booking $booking, string $gateway): View|RedirectResponse
    {
        $customer = Auth::user()->customerProfile;

        abort_if($booking->customer_id !== $customer->id, 403);
        abort_if($booking->status->value !== 'confirmed', 403);
        abort_if(!in_array($gateway, ['khalti', 'esewa']), 400);

        try {
            $result = $this->paymentService->initiateRemaining($booking, $gateway);

            if ($gateway === 'khalti') {
                return redirect()->away($result['payment_url']);
            }

            return view('customer.payments.esewa-redirect', compact('result', 'booking'));
        } catch (\Exception $e) {
            return redirect()->route('customer.bookings.show', $booking)
                ->with('error', 'Payment initiation failed: ' . $e->getMessage());
        }
    }
}
