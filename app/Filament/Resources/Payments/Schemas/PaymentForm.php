<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('booking_id')
                    ->numeric()
                    ->default(null),
                Select::make('type')
                    ->options([
                        'deposit' => 'Deposit',
                        'remaining' => 'Remaining',
                        'refund' => 'Refund',
                    ])
                    ->default(null)
                    ->placeholder('Select type'),
                Select::make('gateway')
                    ->options([
                        'khalti' => 'Khalti',
                        'esewa' => 'Esewa',
                    ])
                    ->default(null)
                    ->placeholder('Select gateway'),
                TextInput::make('gateway_txn_id')
                    ->default(null),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ])
                    ->default(null)
                    ->placeholder('Select status'),
                TextInput::make('amount')
                    ->numeric()
                    ->default(null)
                    ->prefix('NPR'),
                TextInput::make('attempt_number')
                    ->numeric()
                    ->default(null),
                Textarea::make('gateway_response')
                    ->default(null)
                    ->columnSpanFull(),
                DateTimePicker::make('paid_at'),
            ]);
    }
}
