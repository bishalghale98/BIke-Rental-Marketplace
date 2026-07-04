<?php

namespace App\Filament\Resources\Bikes\Schemas;

use App\Enums\BookingStatusEnum;
use App\Models\BikeCategory;
use App\Models\CompanyProfile;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BikeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->placeholder('Select category')
                    ->default(null),
                TextInput::make('brand')
                    ->required(),
                TextInput::make('brand')
                    ->required(),
                TextInput::make('model')
                    ->required(),
                TextInput::make('year')
                    ->default(null),
                TextInput::make('engine_capacity')
                    ->default(null),
                TextInput::make('fuel_type')
                    ->default(null),
                Select::make('transmission')
                    ->options([
                        'Manual' => 'Manual',
                        'Automatic' => 'Automatic',
                        'Semi-Automatic' => 'Semi-Automatic',
                        'CVT' => 'CVT',
                        'DCT' => 'DCT',
                    ])
                    ->default(null)
                    ->placeholder('Select transmission'),
                TextInput::make('mileage')
                    ->default(null),
                TextInput::make('color')
                    ->default(null),
                TextInput::make('bike_number')
                    ->default(null),
                TextInput::make('registration_number')
                    ->default(null),
                TextInput::make('vin')
                    ->default(null),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('features')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('specifications')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('rental_rules')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('hourly_price')
                    ->numeric()
                    ->default(null)
                    ->prefix('NPR'),
                TextInput::make('daily_price')
                    ->numeric()
                    ->default(null)
                    ->prefix('NPR'),
                TextInput::make('weekly_price')
                    ->numeric()
                    ->default(null)
                    ->prefix('NPR'),
                Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'maintenance' => 'Maintenance',
                    ])
                    ->required()
                    ->default('active'),
                Toggle::make('is_available')
                    ->required(),
            ]);
    }
}
