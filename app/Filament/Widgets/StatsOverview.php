<?php

namespace App\Filament\Widgets;

use App\Models\Bike;
use App\Models\Booking;
use App\Models\CompanyProfile;
use App\Models\CompanyVerification;
use App\Models\Payment;
use App\Models\Payout;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('All registered users'),

            Stat::make('Companies', CompanyProfile::count())
                ->description('Registered companies'),

            Stat::make('Total Bikes', Bike::count())
                ->description('Bikes in system'),

            Stat::make('Total Bookings', Booking::count())
                ->description('All bookings'),

            Stat::make('Revenue', 'NPR ' . number_format(Booking::where('status', 'completed')->sum('total_amount'), 2))
                ->description('Completed booking revenue'),

            Stat::make('Commission Earned', 'NPR ' . number_format(Booking::whereIn('status', ['completed', 'confirmed', 'picked_up'])->sum('commission_amount'), 2))
                ->description('Total platform commission'),

            Stat::make('Pending Verifications', CompanyVerification::where('verification_status', 'pending')->count())
                ->description('Awaiting approval'),

            Stat::make('Pending Payouts', 'NPR ' . number_format(Payout::where('status', 'pending')->sum('amount'), 2))
                ->description('Awaiting processing'),

            Stat::make('Failed Payments', Payment::where('status', 'failed')->count())
                ->description('Total failed transactions'),
        ];
    }
}
