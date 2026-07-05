<?php

namespace App\Filament\Resources\Wallet\Pages;

use App\Filament\Resources\Wallet\WalletTransactionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWalletTransaction extends CreateRecord
{
    protected static string $resource = WalletTransactionResource::class;
}
