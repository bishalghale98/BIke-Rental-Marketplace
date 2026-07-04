<?php

namespace App\Filament\Resources\Bookings\Schemas;

use App\Enums\BookingStatusEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('booking_number')
                    ->required(),
                TextInput::make('customer_id')
                    ->required()
                    ->numeric(),
                TextInput::make('bike_id')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('start_date')
                    ->required(),
                DateTimePicker::make('end_date')
                    ->required(),
                TextInput::make('hourly_price')
                    ->numeric()
                    ->default(null)
                    ->prefix('$'),
                TextInput::make('daily_price')
                    ->numeric()
                    ->default(null)
                    ->prefix('$'),
                TextInput::make('weekly_price')
                    ->numeric()
                    ->default(null)
                    ->prefix('$'),
                TextInput::make('hourly_discount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('daily_discount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('weekly_discount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total_hours')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total_days')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total_weeks')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('discount_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Select::make('status')
                    ->options(BookingStatusEnum::class)
                    ->default('pending')
                    ->required(),
                TextInput::make('cancellation_reason')
                    ->default(null),
                TextInput::make('cancelled_by')
                    ->default(null),
                TextInput::make('refund_amount')
                    ->numeric()
                    ->default(null),
                DateTimePicker::make('refund_paid_at'),
                TextInput::make('late_fee')
                    ->numeric()
                    ->default(null),
                DateTimePicker::make('late_fee_paid_at'),
                DateTimePicker::make('extended_until'),
                DateTimePicker::make('cancelled_at'),
            ]);
    }
}
