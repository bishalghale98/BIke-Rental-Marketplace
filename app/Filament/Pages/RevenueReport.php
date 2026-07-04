<?php

namespace App\Filament\Pages;

use App\Models\Booking;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class RevenueReport extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected static ?string $title = 'Revenue Report';
    protected static ?string $slug = 'reports/revenue';
    protected static ?string $navigationLabel = 'Revenue';
    protected static string | \UnitEnum | null $navigationGroup = 'Reports';
    protected static ?int $navigationSort = 1;

    public function getView(): string
    {
        return 'filament.pages.revenue-report';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Booking::where('status', 'completed'))
            ->columns([
                TextColumn::make('booking_number')->label('Booking #')->searchable(),
                TextColumn::make('customer.user.name')->label('Customer'),
                TextColumn::make('bike.name')->label('Bike'),
                TextColumn::make('total_amount')->label('Amount')->money('usd'),
                TextColumn::make('late_fee')->label('Late Fee')->money('usd'),
                TextColumn::make('refund_amount')->label('Refund')->money('usd'),
                TextColumn::make('created_at')->label('Date')->dateTime(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q, $v) => $q->whereDate('created_at', '>=', $v))
                            ->when($data['until'], fn ($q, $v) => $q->whereDate('created_at', '<=', $v));
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
