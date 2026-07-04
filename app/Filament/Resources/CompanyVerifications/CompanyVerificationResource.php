<?php

namespace App\Filament\Resources\CompanyVerifications;

use App\Filament\Resources\CompanyVerifications\Pages\CreateCompanyVerification;
use App\Filament\Resources\CompanyVerifications\Pages\EditCompanyVerification;
use App\Filament\Resources\CompanyVerifications\Pages\ListCompanyVerifications;
use App\Filament\Resources\CompanyVerifications\Schemas\CompanyVerificationForm;
use App\Filament\Resources\CompanyVerifications\Tables\CompanyVerificationsTable;
use App\Models\CompanyVerification;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CompanyVerificationResource extends Resource
{
    protected static ?string $model = CompanyVerification::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return CompanyVerificationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompanyVerificationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompanyVerifications::route('/'),
            'create' => CreateCompanyVerification::route('/create'),
            'edit' => EditCompanyVerification::route('/{record}/edit'),
        ];
    }
}
