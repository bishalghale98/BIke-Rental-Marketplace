<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('company_name')
                    ->required(),
                TextInput::make('owner_name')
                    ->required(),
                TextInput::make('address')
                    ->default(null),
                TextInput::make('contact_number')
                    ->default(null),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('verification_status')
                    ->options([
                        'pending' => 'Pending',
                        'verified' => 'Verified',
                        'rejected' => 'Rejected',
                    ])
                    ->required()
                    ->default('pending'),
                TextInput::make('commission_percent')
                    ->numeric()
                    ->default(null)
                    ->prefix('NPR'),
                Textarea::make('opening_hours')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('social_links')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
