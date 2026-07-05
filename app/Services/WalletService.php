<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\CompanyProfile;
use App\Models\WalletTransaction;

class WalletService
{
    public function credit(CompanyProfile $company, float $amount, string $type, ?Booking $booking = null, ?string $description = null): WalletTransaction
    {
        $currentBalance = $company->balance;
        $newBalance = round($currentBalance + $amount, 2);

        return WalletTransaction::create([
            'company_id' => $company->id,
            'booking_id' => $booking?->id,
            'type' => $type,
            'direction' => 'credit',
            'amount' => $amount,
            'balance_after' => $newBalance,
            'description' => $description,
        ]);
    }

    public function debit(CompanyProfile $company, float $amount, string $type, ?Booking $booking = null, ?string $description = null): WalletTransaction
    {
        $currentBalance = $company->balance;
        $newBalance = round($currentBalance - $amount, 2);

        return WalletTransaction::create([
            'company_id' => $company->id,
            'booking_id' => $booking?->id,
            'type' => $type,
            'direction' => 'debit',
            'amount' => $amount,
            'balance_after' => $newBalance,
            'description' => $description,
        ]);
    }

    public function balance(CompanyProfile $company): float
    {
        return $company->balance;
    }

    public function transactions(CompanyProfile $company)
    {
        return WalletTransaction::where('company_id', $company->id)
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20);
    }
}
