<?php

namespace App\Filament\Resources\Extensions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ExtensionRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('booking_id')
                    ->required()
                    ->numeric(),
                Select::make('customer_id')
                    ->label('Customer')
                    ->relationship('customer', 'full_name')
                    ->placeholder('Select customer')
                    ->default(null),
                DateTimePicker::make('requested_end_date'),
                Textarea::make('reason')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required()
                    ->default('pending'),
                DateTimePicker::make('handled_at'),
            ]);
    }
}
