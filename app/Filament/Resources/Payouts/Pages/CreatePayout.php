<?php

namespace App\Filament\Resources\Payouts\Pages;

use App\Filament\Resources\Payouts\PayoutResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePayout extends CreateRecord
{
    protected static string $resource = PayoutResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['bank_details']) && is_array($data['bank_details'])) {
            $data['bank_details'] = array_filter($data['bank_details']);
        }

        return $data;
    }
}
