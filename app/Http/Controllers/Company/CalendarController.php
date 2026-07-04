<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function index(Request $request): View
    {
        $company = Auth::user()->company;
        $bikeIds = $company->bikes()->pluck('id');

        $month = $request->month ? Carbon::parse($request->month) : now()->startOfMonth();
        $monthStart = $month->copy()->startOfMonth()->startOfWeek();
        $monthEnd = $month->copy()->endOfMonth()->endOfWeek();

        $bookings = Booking::whereIn('bike_id', $bikeIds)
            ->where('start_date', '<=', $monthEnd)
            ->where('end_date', '>=', $monthStart)
            ->with('bike', 'customer.user')
            ->get();

        $weeks = [];
        $current = $monthStart->copy();
        while ($current->lte($monthEnd)) {
            $week = [];
            for ($i = 0; $i < 7; $i++) {
                $day = $current->copy();
                $dayBookings = $bookings->filter(fn ($b) =>
                    $b->start_date->lte($day->endOfDay()) && $b->effective_end_date->gte($day->startOfDay())
                );
                $week[] = compact('day', 'dayBookings');
                $current->addDay();
            }
            $weeks[] = $week;
        }

        return view('company.calendar.index', compact('weeks', 'month'));
    }
}
