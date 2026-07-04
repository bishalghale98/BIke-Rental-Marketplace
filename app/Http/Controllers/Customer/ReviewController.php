<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\CustomerProfile;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReviewController extends Controller
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

        $reviews = Review::where('customer_id', $customer->id)
            ->with('bike.images', 'booking')
            ->latest()
            ->paginate(10);

        return view('customer.reviews.index', compact('reviews'));
    }

    public function create(Booking $booking): View|RedirectResponse
    {
        $customer = $this->getCustomer();

        if (!$customer) {
            return redirect()->route('customer.dashboard');
        }

        abort_if($booking->customer_id !== $customer->id, 403);
        abort_if($booking->status->value !== 'completed', 403);
        abort_if($booking->review()->exists(), 403);

        $booking->load('bike.images', 'bike.company');

        return view('customer.reviews.create', compact('booking'));
    }

    public function store(Request $request, Booking $booking): RedirectResponse
    {
        $customer = $this->getCustomer();

        if (!$customer) {
            return redirect()->route('customer.dashboard');
        }

        abort_if($booking->customer_id !== $customer->id, 403);
        abort_if($booking->status->value !== 'completed', 403);
        abort_if($booking->review()->exists(), 403);

        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['nullable', 'string', 'max:5000'],
        ]);

        Review::create([
            'booking_id' => $booking->id,
            'customer_id' => $customer->id,
            'bike_id' => $booking->bike_id,
            'company_id' => $booking->bike->company_id,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return redirect()->route('customer.reviews.index')
            ->with('success', 'Review submitted successfully.');
    }
}
