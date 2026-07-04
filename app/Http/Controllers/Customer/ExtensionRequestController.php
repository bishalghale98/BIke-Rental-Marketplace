<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\CustomerProfile;
use App\Models\ExtensionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ExtensionRequestController extends Controller
{
    private function getCustomer(): ?CustomerProfile
    {
        return Auth::user()->customerProfile;
    }

    public function create(Booking $booking): View|RedirectResponse
    {
        $customer = $this->getCustomer();

        if (!$customer) {
            return redirect()->route('customer.dashboard');
        }

        abort_if($booking->customer_id !== $customer->id || $booking->status->value !== 'ongoing', 403);

        return view('customer.extensions.create', compact('booking'));
    }

    public function store(Request $request, Booking $booking): RedirectResponse
    {
        $customer = $this->getCustomer();

        if (!$customer) {
            return redirect()->route('customer.dashboard');
        }

        abort_if($booking->customer_id !== $customer->id || $booking->status->value !== 'ongoing', 403);

        $request->validate([
            'requested_end_date' => ['required', 'date', 'after:' . $booking->effective_end_date],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $exists = ExtensionRequest::where('booking_id', $booking->id)
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return back()->with('error', 'You already have a pending extension request for this booking.');
        }

        ExtensionRequest::create([
            'booking_id' => $booking->id,
            'customer_id' => $customer->id,
            'requested_end_date' => $request->requested_end_date,
            'reason' => $request->reason,
        ]);

        return redirect()->route('customer.bookings.show', $booking)
            ->with('success', 'Extension request submitted.');
    }

    public function index(): View|RedirectResponse
    {
        $customer = $this->getCustomer();

        if (!$customer) {
            return redirect()->route('customer.dashboard');
        }

        $requests = ExtensionRequest::where('customer_id', $customer->id)
            ->with('booking.bike')
            ->latest()
            ->paginate(15);

        return view('customer.extensions.index', compact('requests'));
    }
}
