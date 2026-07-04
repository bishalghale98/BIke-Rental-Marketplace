<?php

namespace App\Http\Controllers\Company;

use App\Enums\BookingStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Bike;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $company = Auth::user()->company;

        return view('company.reports.index');
    }

    public function revenue(Request $request): View
    {
        $company = Auth::user()->company;
        $bikeIds = $company->bikes()->pluck('id');

        $from = $request->date('from') ?? now()->startOfMonth();
        $to = $request->date('to') ?? now()->endOfDay();

        $daily = Booking::whereIn('bike_id', $bikeIds)
            ->where('status', BookingStatusEnum::Completed)
            ->whereBetween('updated_at', [$from->startOfDay(), $to->endOfDay()])
            ->select(DB::raw('DATE(updated_at) as date'), DB::raw('COUNT(*) as bookings'), DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $summary = Booking::whereIn('bike_id', $bikeIds)
            ->where('status', BookingStatusEnum::Completed)
            ->whereBetween('updated_at', [$from->startOfDay(), $to->endOfDay()])
            ->select(DB::raw('COUNT(*) as total_bookings'), DB::raw('SUM(total_amount) as total_revenue'), DB::raw('AVG(total_amount) as avg_revenue'))
            ->first();

        return view('company.reports.revenue', compact('daily', 'summary', 'from', 'to'));
    }

    public function bookings(Request $request): View
    {
        $company = Auth::user()->company;
        $bikeIds = $company->bikes()->pluck('id');

        $from = $request->date('from') ?? now()->startOfMonth();
        $to = $request->date('to') ?? now()->endOfDay();

        $bookings = Booking::whereIn('bike_id', $bikeIds)
            ->whereBetween('created_at', [$from->startOfDay(), $to->endOfDay()])
            ->with('bike', 'customer.user')
            ->latest()
            ->paginate(20);

        return view('company.reports.bookings', compact('bookings', 'from', 'to'));
    }

    public function bikes(Request $request): View
    {
        $company = Auth::user()->company;
        $bikeIds = $company->bikes()->pluck('id');

        $from = $request->date('from') ?? now()->startOfMonth();
        $to = $request->date('to') ?? now()->endOfDay();

        $bikes = Bike::whereIn('id', $bikeIds)
            ->with(['images' => fn($q) => $q->where('is_primary', true)])
            ->withCount(['bookings as total_bookings' => fn($q) => $q->where('status', BookingStatusEnum::Completed)->whereBetween('updated_at', [$from->startOfDay(), $to->endOfDay()])])
            ->withCount(['bookings as total_revenue' => fn($q) => $q->where('status', BookingStatusEnum::Completed)->whereBetween('updated_at', [$from->startOfDay(), $to->endOfDay()])->select(DB::raw('coalesce(sum(total_amount), 0)'))])
            ->orderByDesc('total_bookings')
            ->get();

        $summary = Booking::whereIn('bike_id', $bikeIds)
            ->where('status', BookingStatusEnum::Completed)
            ->whereBetween('updated_at', [$from->startOfDay(), $to->endOfDay()])
            ->select(DB::raw('COUNT(*) as total_bookings'), DB::raw('SUM(total_amount) as total_revenue'))
            ->first();

        return view('company.reports.bikes', compact('bikes', 'summary', 'from', 'to'));
    }

    public function exportRevenue(Request $request): Response
    {
        $company = Auth::user()->company;
        $bikeIds = $company->bikes()->pluck('id');

        $from = $request->date('from') ?? now()->startOfMonth();
        $to = $request->date('to') ?? now()->endOfDay();

        $daily = Booking::whereIn('bike_id', $bikeIds)
            ->where('status', BookingStatusEnum::Completed)
            ->whereBetween('updated_at', [$from->startOfDay(), $to->endOfDay()])
            ->select(DB::raw('DATE(updated_at) as date'), DB::raw('COUNT(*) as bookings'), DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $filename = 'revenue-report-' . now()->format('Ymd') . '.csv';
        $handle = fopen('php://temp', 'w+');
        fputcsv($handle, ['Date', 'Bookings', 'Revenue']);
        foreach ($daily as $row) {
            fputcsv($handle, [$row->date, $row->bookings, $row->revenue]);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    public function exportBookings(Request $request): Response
    {
        $company = Auth::user()->company;
        $bikeIds = $company->bikes()->pluck('id');

        $from = $request->date('from') ?? now()->startOfMonth();
        $to = $request->date('to') ?? now()->endOfDay();

        $bookings = Booking::whereIn('bike_id', $bikeIds)
            ->whereBetween('created_at', [$from->startOfDay(), $to->endOfDay()])
            ->with('bike', 'customer.user')
            ->latest()
            ->get();

        $filename = 'bookings-report-' . now()->format('Ymd') . '.csv';
        $handle = fopen('php://temp', 'w+');
        fputcsv($handle, ['Booking #', 'Customer', 'Bike', 'Start', 'End', 'Amount', 'Status', 'Date']);
        foreach ($bookings as $b) {
            fputcsv($handle, [$b->booking_number, $b->customer?->user?->name, $b->bike?->name, $b->start_date, $b->end_date, $b->total_amount, $b->status->value, $b->created_at]);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    public function exportBikes(Request $request): Response
    {
        $company = Auth::user()->company;
        $bikeIds = $company->bikes()->pluck('id');

        $from = $request->date('from') ?? now()->startOfMonth();
        $to = $request->date('to') ?? now()->endOfDay();

        $bikes = Bike::whereIn('id', $bikeIds)
            ->withCount(['bookings as total_bookings' => fn($q) => $q->where('status', BookingStatusEnum::Completed)->whereBetween('updated_at', [$from->startOfDay(), $to->endOfDay()])])
            ->withCount(['bookings as total_revenue' => fn($q) => $q->where('status', BookingStatusEnum::Completed)->whereBetween('updated_at', [$from->startOfDay(), $to->endOfDay()])->select(DB::raw('coalesce(sum(total_amount), 0)'))])
            ->orderByDesc('total_bookings')
            ->get();

        $filename = 'bike-performance-report-' . now()->format('Ymd') . '.csv';
        $handle = fopen('php://temp', 'w+');
        fputcsv($handle, ['Bike', 'Category', 'Bookings', 'Revenue']);
        foreach ($bikes as $bike) {
            fputcsv($handle, [$bike->name, $bike->category?->name, $bike->total_bookings, $bike->total_revenue]);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
}
