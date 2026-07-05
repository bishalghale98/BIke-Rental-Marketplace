<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\CompanyProfile;
use App\Models\Payment;
use App\Models\Payout;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinancialController extends Controller
{
    public function dashboard(Request $request): View
    {
        $startDate = $request->date('start_date', now()->startOfMonth());
        $endDate = $request->date('end_date', now()->endOfMonth());

        $totalCommission = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['completed', 'confirmed', 'picked_up'])
            ->sum('commission_amount');

        $totalRevenue = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['completed', 'confirmed', 'picked_up'])
            ->sum('total_amount');

        $pendingPayouts = Payout::where('status', 'pending')->sum('amount');
        $completedPayouts = Payout::where('status', 'paid')->sum('amount');

        $failedPayments = Payment::where('status', 'failed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $totalRefunds = Payment::where('type', 'refund')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $companyCommissions = CompanyProfile::select('id', 'company_name', 'commission_percent')
            ->withCount(['bikes'])
            ->get()
            ->map(function ($company) use ($startDate, $endDate) {
                $company->booking_count = Booking::whereIn('bike_id', $company->bikes()->pluck('id'))
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->whereIn('status', ['completed', 'confirmed', 'picked_up'])
                    ->count();
                $company->commission_earned = Booking::whereIn('bike_id', $company->bikes()->pluck('id'))
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->whereIn('status', ['completed', 'confirmed', 'picked_up'])
                    ->sum('commission_amount');
                return $company;
            });

        $recentTransactions = WalletTransaction::with('company.user')
            ->latest()
            ->take(20)
            ->get();

        return view('admin.financial.dashboard', compact(
            'totalCommission',
            'totalRevenue',
            'pendingPayouts',
            'completedPayouts',
            'failedPayments',
            'totalRefunds',
            'companyCommissions',
            'recentTransactions',
            'startDate',
            'endDate',
        ));
    }
}
