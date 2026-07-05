<?php

namespace App\Services;

use App\Models\CompanyProfile;
use App\Models\Payout;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PayoutService
{
    private const VALID_TRANSITIONS = [
        'pending'    => ['processing', 'failed'],
        'processing' => ['paid', 'failed'],
        'paid'       => [],
        'failed'     => [],
    ];

    public function __construct(
        private WalletService $walletService,
    ) {}

    public function request(CompanyProfile $company, float $amount, array $bankDetails, ?string $notes = null): Payout
    {
        if ($company->balance < $amount) {
            throw new \RuntimeException('Insufficient balance for payout request.');
        }

        return DB::transaction(function () use ($company, $amount, $bankDetails, $notes) {
            $payout = Payout::create([
                'company_id'   => $company->id,
                'amount'       => $amount,
                'status'       => 'pending',
                'bank_details' => $bankDetails,
                'notes'        => $notes,
            ]);

            $this->walletService->debit(
                $company,
                $amount,
                'payout_hold',
                null,
                "Payout #{$payout->id} requested",
            );

            return $payout;
        });
    }

    public function approve(Payout $payout, User $admin): void
    {
        DB::transaction(function () use ($payout, $admin) {
            $fresh = Payout::query()->lockForUpdate()->findOrFail($payout->id);

            $this->assertValidTransition($fresh, 'processing');

            $fresh->update([
                'status'        => 'processing',
                'processed_by'  => $admin->id,
            ]);
        });
    }

    public function markPaid(Payout $payout, ?string $paymentProof = null): void
    {
        DB::transaction(function () use ($payout, $paymentProof) {
            $fresh = Payout::query()->lockForUpdate()->findOrFail($payout->id);

            $this->assertValidTransition($fresh, 'paid');

            $data = [
                'status'  => 'paid',
                'paid_at' => now(),
            ];

            if ($paymentProof) {
                $data['payment_proof'] = $paymentProof;
            }

            $fresh->update($data);
        });
    }

    public function markFailed(Payout $payout, string $reason): void
    {
        DB::transaction(function () use ($payout, $reason) {
            $fresh = Payout::query()->with('company')->lockForUpdate()->findOrFail($payout->id);

            $this->assertValidTransition($fresh, 'failed');

            $this->walletService->credit(
                $fresh->company,
                $fresh->amount,
                'payout_reversal',
                null,
                "Payout #{$fresh->id} failed, funds released",
            );

            $fresh->update([
                'status' => 'failed',
                'notes'  => $reason,
            ]);
        });
    }

    private function assertValidTransition(Payout $payout, string $targetStatus): void
    {
        $allowed = self::VALID_TRANSITIONS[$payout->status] ?? [];

        if (!in_array($targetStatus, $allowed, true)) {
            throw new \RuntimeException(
                sprintf(
                    'Invalid payout state transition: "%s" → "%s". Allowed transitions from "%s": %s.',
                    $payout->status,
                    $targetStatus,
                    $payout->status,
                    $allowed ? implode(', ', $allowed) : 'none',
                ),
            );
        }
    }
}
