<?php

namespace App\Filament\Resources\CompanyVerifications\Pages;

use App\Filament\Resources\CompanyVerifications\CompanyVerificationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCompanyVerification extends EditRecord
{
    protected static string $resource = CompanyVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
