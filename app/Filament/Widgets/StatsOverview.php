<?php

namespace App\Filament\Widgets;

use App\Models\Bike;
use App\Models\Booking;
use App\Models\CompanyVerification;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count()),
            Stat::make('Total Bikes', Bike::count()),
            Stat::make('Total Bookings', Booking::count()),
            Stat::make('Revenue', '$' . number_format(Booking::where('status', 'completed')->sum('total_amount'), 2)),
            Stat::make('Pending Verifications', CompanyVerification::where('verification_status', 'pending')->count()),
        ];
    }
}
