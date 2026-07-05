<?php

namespace App\Filament\Resources\Payouts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Schema;

class PayoutForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('company_id')
                    ->relationship('company', 'company_name')
                    ->required()
                    ->disabled(),
                TextInput::make('amount')
                    ->numeric()
                    ->required()
                    ->prefix('NPR'),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                    ])
                    ->required()
                    ->default('pending'),
                TextInput::make('bank_details.bank_name')
                    ->label('Bank Name'),
                TextInput::make('bank_details.account_name')
                    ->label('Account Name'),
                TextInput::make('bank_details.account_number')
                    ->label('Account Number'),
                TextInput::make('bank_details.branch')
                    ->label('Branch'),
                ViewField::make('qr_preview')
                    ->label('QR Code')
                    ->view('filament.components.payout-qr')
                    ->columnSpanFull(),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
                ViewField::make('payment_proof_preview')
                    ->label('Payment Screenshot')
                    ->view('filament.components.payment-proof')
                    ->columnSpanFull(),
                TextInput::make('processor.name')
                    ->label('Processed by')
                    ->disabled(),
                DateTimePicker::make('paid_at')
                    ->disabled(),
            ]);
    }
}
