<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\CompanyProfile;

class CommissionService
{
    public function calculateForBooking(Booking $booking): array
    {
        $company = $booking->bike->company;
        $percent = $company->commission_percent ?? config('marketplace.default_commission_percent');
        $amount = round($booking->total_amount * ($percent / 100), 2);
        $earnings = round($booking->total_amount - $amount, 2);

        return [
            'commission_percent' => $percent,
            'commission_amount' => $amount,
            'company_earnings' => $earnings,
        ];
    }

    public function snapshotOnConfirm(Booking $booking): void
    {
        $commission = $this->calculateForBooking($booking);
        $booking->update($commission);
    }
}
