<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Services\PayoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PayoutController extends Controller
{
    public function __construct(
        private PayoutService $payoutService,
    ) {}

    public function index(): View
    {
        $payouts = Payout::with('company.user')
            ->latest()
            ->paginate(20);

        $stats = [
            'pending' => Payout::where('status', 'pending')->sum('amount'),
            'processing' => Payout::where('status', 'processing')->sum('amount'),
            'paid_this_month' => Payout::where('status', 'paid')
                ->whereMonth('paid_at', now()->month)
                ->sum('amount'),
        ];

        return view('admin.payouts.index', compact('payouts', 'stats'));
    }

    public function show(Payout $payout): View
    {
        $payout->load('company.user', 'processor');

        return view('admin.payouts.show', compact('payout'));
    }

    public function approve(Payout $payout): RedirectResponse
    {
        if ($payout->status !== 'pending') {
            return back()->with('error', 'Only pending payouts can be approved.');
        }

        $this->payoutService->approve($payout, Auth::user());

        return redirect()->route('admin.payouts.show', $payout)
            ->with('success', 'Payout approved. Mark as paid once transferred.');
    }

    public function markPaid(Payout $payout): RedirectResponse
    {
        if ($payout->status !== 'processing') {
            return back()->with('error', 'Payout must be in processing status first.');
        }

        $this->payoutService->markPaid($payout);

        return redirect()->route('admin.payouts.show', $payout)
            ->with('success', 'Payout marked as paid.');
    }

    public function markFailed(Request $request, Payout $payout): RedirectResponse
    {
        $request->validate(['reason' => ['required', 'string', 'max:500']]);

        $this->payoutService->markFailed($payout, $request->reason);

        return redirect()->route('admin.payouts.show', $payout)
            ->with('success', 'Payout marked as failed.');
    }
}
