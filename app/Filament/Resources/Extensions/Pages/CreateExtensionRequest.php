<?php

namespace App\Filament\Resources\Extensions\Pages;

use App\Filament\Resources\Extensions\ExtensionRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateExtensionRequest extends CreateRecord
{
    protected static string $resource = ExtensionRequestResource::class;
}
