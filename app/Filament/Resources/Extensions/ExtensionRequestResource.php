<?php

namespace App\Filament\Resources\Extensions;

use App\Filament\Resources\Extensions\Pages\CreateExtensionRequest;
use App\Filament\Resources\Extensions\Pages\EditExtensionRequest;
use App\Filament\Resources\Extensions\Pages\ListExtensionRequests;
use App\Filament\Resources\Extensions\Schemas\ExtensionRequestForm;
use App\Filament\Resources\Extensions\Tables\ExtensionRequestsTable;
use App\Models\ExtensionRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ExtensionRequestResource extends Resource
{
    protected static ?string $model = ExtensionRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return ExtensionRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExtensionRequestsTable::configure($table);
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
            'index' => ListExtensionRequests::route('/'),
            'create' => CreateExtensionRequest::route('/create'),
            'edit' => EditExtensionRequest::route('/{record}/edit'),
        ];
    }
}
