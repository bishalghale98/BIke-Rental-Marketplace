<?php

namespace App\Http\Controllers\Company;

use App\Enums\BookingStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Bike;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $company = Auth::user()->company;
        $bikeIds = $company->bikes()->pluck('id');

        $totalBikes = $company->bikes()->count();
        $availableBikes = $company->bikes()->where('is_available', true)->where('status', 'active')->count();

        $activeBookings = Booking::whereIn('bike_id', $bikeIds)
            ->where('status', BookingStatusEnum::Ongoing)
            ->count();

        $totalRevenue = Booking::whereIn('bike_id', $bikeIds)
            ->where('status', BookingStatusEnum::Completed)
            ->sum('total_amount');

        $recentBookings = Booking::whereIn('bike_id', $bikeIds)
            ->with('bike', 'customer.user')
            ->latest()
            ->take(10)
            ->get();

        $revenueData = Booking::whereIn('bike_id', $bikeIds)
            ->where('status', BookingStatusEnum::Completed)
            ->where('updated_at', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(updated_at) as date'), DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $dates = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates->push([
                'date' => $date,
                'revenue' => (float) ($revenueData[$date]->revenue ?? 0),
            ]);
        }

        $topBikes = Booking::whereIn('bike_id', $bikeIds)
            ->where('status', BookingStatusEnum::Completed)
            ->select('bike_id', DB::raw('COUNT(*) as total_bookings'), DB::raw('SUM(total_amount) as total_revenue'))
            ->groupBy('bike_id')
            ->orderByDesc('total_bookings')
            ->with('bike.images')
            ->take(5)
            ->get();

        return view('company.dashboard', compact(
            'totalBikes',
            'availableBikes',
            'activeBookings',
            'totalRevenue',
            'recentBookings',
            'dates',
            'topBikes',
        ));
    }
}
