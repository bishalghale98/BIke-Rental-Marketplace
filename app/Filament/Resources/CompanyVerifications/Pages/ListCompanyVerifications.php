<?php

namespace App\Filament\Resources\CompanyVerifications\Pages;

use App\Filament\Resources\CompanyVerifications\CompanyVerificationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCompanyVerifications extends ListRecords
{
    protected static string $resource = CompanyVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
