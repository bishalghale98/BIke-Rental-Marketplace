<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Services\PayoutService;
use App\Services\WalletService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PayoutController extends Controller
{
    public function __construct(
        private PayoutService $payoutService,
        private WalletService $walletService,
    ) {}

    public function index(): View
    {
        $company = Auth::user()->company;
        $balance = $this->walletService->balance($company);
        $payouts = Payout::where('company_id', $company->id)->latest()->paginate(15);

        return view('company.payouts.index', compact('company', 'balance', 'payouts'));
    }

    public function request(Request $request): RedirectResponse
    {
        $company = Auth::user()->company;

        $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:' . $company->balance],
            'bank_name' => ['required', 'string', 'max:255'],
            'bank_account_name' => ['required', 'string', 'max:255'],
            'bank_account_number' => ['required', 'string', 'max:50'],
            'bank_branch' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $this->payoutService->request(
                $company,
                $request->amount,
                $request->only(['bank_name', 'bank_account_name', 'bank_account_number', 'bank_branch']),
                $request->notes
            );

            return redirect()->route('company.payouts.index')
                ->with('success', 'Payout request submitted successfully.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function history(): View
    {
        $company = Auth::user()->company;
        $transactions = $this->walletService->transactions($company);

        return view('company.payouts.history', compact('company', 'transactions'));
    }
}
