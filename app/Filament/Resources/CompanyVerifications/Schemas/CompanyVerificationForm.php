<?php

namespace App\Filament\Resources\CompanyVerifications\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CompanyVerificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('company_id')
                    ->required()
                    ->numeric(),
                TextInput::make('registration_certificate')
                    ->default(null),
                TextInput::make('pan_certificate')
                    ->default(null),
                TextInput::make('owner_citizenship')
                    ->default(null),
                TextInput::make('owner_photo')
                    ->default(null),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                Textarea::make('rejected_reason')
                    ->default(null)
                    ->columnSpanFull(),
                DateTimePicker::make('verified_at'),
            ]);
    }
}
