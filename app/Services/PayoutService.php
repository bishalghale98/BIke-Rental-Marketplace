<?php

namespace App\Services;

use App\Models\CompanyProfile;
use App\Models\Payout;
use App\Models\User;

class PayoutService
{
    public function __construct(
        private WalletService $walletService,
    ) {}

    public function request(CompanyProfile $company, float $amount, array $bankDetails, ?string $notes = null): Payout
    {
        if ($company->balance < $amount) {
            throw new \RuntimeException('Insufficient balance for payout request.');
        }

        return Payout::create([
            'company_id' => $company->id,
            'amount' => $amount,
            'status' => 'pending',
            'bank_details' => $bankDetails,
            'notes' => $notes,
        ]);
    }

    public function approve(Payout $payout, User $admin): void
    {
        $payout->update([
            'status' => 'processing',
            'processed_by' => $admin->id,
        ]);
    }

    public function markPaid(Payout $payout): void
    {
        $this->walletService->debit(
            $payout->company,
            $payout->amount,
            'payout_debit',
            null,
            "Payout #{$payout->id} processed"
        );

        $payout->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    public function markFailed(Payout $payout, string $reason): void
    {
        $payout->update([
            'status' => 'failed',
            'notes' => $reason,
        ]);
    }
}
