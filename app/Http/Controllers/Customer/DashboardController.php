<?php

namespace App\Http\Controllers\Customer;

use App\Enums\BookingStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $customer = $user->customerProfile;

        if (!$customer) {
            return view('customer.dashboard', [
                'showOnboarding' => true,
                'customer' => null,
            ]);
        }

        $isVerified = $customer->verification_status === 'verified';

        if (!$isVerified) {
            return view('customer.dashboard', [
                'showOnboarding' => false,
                'customer' => $customer,
                'isVerified' => false,
                'verificationStatus' => $customer->verification_status,
            ]);
        }

        return view('customer.dashboard', compact(
            'customer',
        ) + [
            'isVerified' => true,
            'showOnboarding' => false,
            'upcomingCount' => Booking::where('customer_id', $customer->id)
                ->whereIn('status', [BookingStatusEnum::Pending, BookingStatusEnum::Confirmed])
                ->count(),
            'activeCount' => Booking::where('customer_id', $customer->id)
                ->where('status', BookingStatusEnum::Ongoing)
                ->count(),
            'completedCount' => Booking::where('customer_id', $customer->id)
                ->where('status', BookingStatusEnum::Completed)
                ->count(),
            'upcomingBookings' => Booking::where('customer_id', $customer->id)
                ->whereIn('status', [BookingStatusEnum::Pending, BookingStatusEnum::Confirmed, BookingStatusEnum::Ongoing])
                ->with('bike.images', 'bike.category')
                ->latest('start_date')
                ->take(5)
                ->get(),
            'recentCompleted' => Booking::where('customer_id', $customer->id)
                ->where('status', BookingStatusEnum::Completed)
                ->with('bike.images')
                ->latest('updated_at')
                ->take(5)
                ->get(),
        ]);
    }
}
