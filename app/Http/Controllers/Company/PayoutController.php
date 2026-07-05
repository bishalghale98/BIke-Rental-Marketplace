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
        $bankDetails = $company->bankDetails()->latest()->get();

        return view('company.payouts.index', compact('company', 'balance', 'payouts', 'bankDetails'));
    }

    public function request(Request $request): RedirectResponse
    {
        $company = Auth::user()->company;

        $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:' . $company->balance],
            'bank_detail_id' => ['required', 'exists:bank_details,id'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $bankDetail = $company->bankDetails()->findOrFail($request->bank_detail_id);

        $bankDetails = [
            'bank_name' => $bankDetail->bank_name,
            'account_name' => $bankDetail->account_name,
            'account_number' => $bankDetail->account_number,
            'branch' => $bankDetail->branch,
        ];

        try {
            $this->payoutService->request(
                $company,
                $request->amount,
                $bankDetails,
                $request->notes
            );

            return redirect()->route('company.payouts.index')
                ->with('success', 'Payout request submitted successfully.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function invoice(Payout $payout): View
    {
        $company = Auth::user()->company;

        abort_if($payout->company_id !== $company->id, 403);

        return view('company.payouts.invoice', compact('company', 'payout'));
    }

    public function history(): View
    {
        $company = Auth::user()->company;
        $transactions = $this->walletService->transactions($company);

        return view('company.payouts.history', compact('company', 'transactions'));
    }
}
