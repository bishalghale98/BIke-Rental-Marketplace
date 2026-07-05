<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ReviewForm
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
                Select::make('bike_id')
                    ->label('Bike')
                    ->relationship('bike', 'name')
                    ->placeholder('Select bike')
                    ->default(null),
                Select::make('company_id')
                    ->label('Company')
                    ->relationship('company', 'company_name')
                    ->placeholder('Select company')
                    ->default(null),
                Select::make('rating')
                    ->options([
                        1 => '1',
                        2 => '2',
                        3 => '3',
                        4 => '4',
                        5 => '5',
                    ])
                    ->default(null)
                    ->placeholder('Select rating'),
                Textarea::make('review')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('reply')
                    ->default(null)
                    ->columnSpanFull(),
                DateTimePicker::make('replied_at'),
            ]);
    }
}
