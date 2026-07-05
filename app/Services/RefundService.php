<?php

namespace App\Services;

use App\Enums\BookingStatusEnum;
use App\Models\Booking;
use Carbon\Carbon;

class RefundService
{
    public function calculateRefund(Booking $booking): float
    {
        $hoursBeforeStart = now()->diffInHours($booking->start_date, false);

        return match (true) {
            $hoursBeforeStart >= 24 => $booking->total_amount,
            $hoursBeforeStart >= 0 => $booking->total_amount * 0.5,
            default => 0,
        };
    }

    public function calculateRefundForCancellation(Booking $booking, string $cancelledBy, ?Carbon $overrideNow = null): float
    {
        $now = $overrideNow ?? now();

        if ($cancelledBy === 'company') {
            return $booking->total_amount;
        }

        $hoursBeforeStart = $now->diffInHours($booking->start_date, false);

        return match (true) {
            $hoursBeforeStart >= 24 => $booking->total_amount,
            $hoursBeforeStart >= 0 => $booking->total_amount * 0.5,
            default => 0,
        };
    }
}
