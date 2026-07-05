<?php

namespace App\Filament\Resources\Wallet;

use App\Filament\Resources\Wallet\Pages\CreateWalletTransaction;
use App\Filament\Resources\Wallet\Pages\EditWalletTransaction;
use App\Filament\Resources\Wallet\Pages\ListWalletTransactions;
use App\Filament\Resources\Wallet\Schemas\WalletTransactionForm;
use App\Filament\Resources\Wallet\Tables\WalletTransactionsTable;
use App\Models\WalletTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WalletTransactionResource extends Resource
{
    protected static ?string $model = WalletTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return WalletTransactionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WalletTransactionsTable::configure($table);
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
            'index' => ListWalletTransactions::route('/'),
            'create' => CreateWalletTransaction::route('/create'),
            'edit' => EditWalletTransaction::route('/{record}/edit'),
        ];
    }
}
