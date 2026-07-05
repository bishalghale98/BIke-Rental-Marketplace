<?php

namespace App\Filament\Resources\Wallet\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class WalletTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('company_id')
                    ->label('Company')
                    ->relationship('company', 'company_name')
                    ->placeholder('Select company')
                    ->default(null),
                TextInput::make('booking_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('type')
                    ->default(null),
                Select::make('direction')
                    ->options([
                        'credit' => 'Credit',
                        'debit' => 'Debit',
                    ])
                    ->default(null)
                    ->placeholder('Select direction'),
                TextInput::make('amount')
                    ->numeric()
                    ->default(null)
                    ->prefix('NPR'),
                TextInput::make('balance_after')
                    ->numeric()
                    ->default(null)
                    ->prefix('NPR'),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
