<?php

namespace App\Services;

use App\Models\Bike;
use Carbon\Carbon;

class BookingPricingService
{
    public function calculate(Carbon $start, Carbon $end, Bike $bike): array
    {
        $totalHours = $start->diffInHours($end);
        $totalDays = $start->diffInDays($end) + 1;
        $totalWeeks = floor($totalDays / 7);
        $remainingDays = $totalDays % 7;

        $subtotal = 0;
        $totalHoursCalc = 0;
        $totalDaysCalc = 0;
        $totalWeeksCalc = 0;

        if ($bike->weekly_price && $totalWeeks > 0) {
            $totalWeeksCalc = $totalWeeks;
            $subtotal += $totalWeeks * $bike->weekly_price;
        }

        if ($bike->daily_price && $remainingDays > 0) {
            $totalDaysCalc = $remainingDays;
            $subtotal += $remainingDays * $bike->daily_price;
        } elseif ($bike->daily_price && $totalWeeks === 0 && $totalHours < 24) {
            $totalDaysCalc = 1;
            $subtotal += $bike->daily_price;
        }

        if ($bike->hourly_price && $subtotal === 0 && $totalHours < 24) {
            $totalHoursCalc = $totalHours;
            $subtotal += $totalHours * $bike->hourly_price;
        }

        return [
            'total_hours' => $totalHoursCalc,
            'total_days' => $totalDaysCalc,
            'total_weeks' => $totalWeeksCalc,
            'subtotal' => $subtotal,
            'total_amount' => $subtotal,
        ];
    }
}
