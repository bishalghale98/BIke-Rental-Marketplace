<?php

namespace App\Filament\Pages;

use App\Models\CompanyProfile;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class CompanyPerformanceReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $title = 'Company Performance';
    protected static ?string $slug = 'reports/companies';
    protected static ?string $navigationLabel = 'Company Performance';
    protected static string | \UnitEnum | null $navigationGroup = 'Reports';
    protected static ?int $navigationSort = 2;

    public function getView(): string
    {
        return 'filament.pages.company-performance-report';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                CompanyProfile::query()
                    ->withCount('bikes')
                    ->withCount(['bikes as bookings_count' => function ($q) {
                        $q->join('bookings', 'bikes.id', '=', 'bookings.bike_id')
                            ->where('bookings.status', 'completed');
                    }])
                    ->withSum(['bikes as revenue' => function ($q) {
                        $q->join('bookings', 'bikes.id', '=', 'bookings.bike_id')
                            ->where('bookings.status', 'completed');
                    }], 'bookings.total_amount')
            )
            ->columns([
                TextColumn::make('company_name')->label('Company')->searchable()->sortable(),
                TextColumn::make('user.name')->label('Owner'),
                TextColumn::make('bikes_count')->label('Bikes')->sortable(),
                TextColumn::make('bookings_count')->label('Bookings')->sortable(),
                TextColumn::make('revenue')->label('Revenue')->money('usd')->sortable(),
                TextColumn::make('created_at')->label('Joined')->date(),
            ])
            ->defaultSort('revenue', 'desc');
    }
}
