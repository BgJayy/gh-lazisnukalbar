<?php

namespace App\Filament\Resources\BankAccounts;

use App\Enums\FundBook;
use App\Filament\Resources\BankAccounts\Pages\CreateBankAccount;
use App\Filament\Resources\BankAccounts\Pages\EditBankAccount;
use App\Filament\Resources\BankAccounts\Pages\ListBankAccounts;
use App\Models\BankAccount;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BankAccountResource extends Resource
{
    protected static ?string $model = BankAccount::class;

    protected static ?string $navigationLabel = 'Rekening';

    protected static ?string $modelLabel = 'Rekening';

    protected static ?string $pluralModelLabel = 'Rekening';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static string|\UnitEnum|null $navigationGroup = 'Keuangan';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'account_number';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('bank_name')->label('Bank')->required()->maxLength(255),
                TextInput::make('account_number')->label('Nomor rekening')->required()->maxLength(50),
                TextInput::make('account_holder')->label('Atas nama')->required()->maxLength(255),
                Select::make('fund_book')->label('Buku kas')->options(FundBook::options())->required()->native(false),
                Toggle::make('is_active')->label('Aktif')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bank_name')->label('Bank')->searchable(),
                TextColumn::make('account_number')->label('Nomor')->searchable(),
                TextColumn::make('account_holder')->label('Atas nama'),
                TextColumn::make('fund_book')->label('Buku kas')->badge(),
                TextColumn::make('is_active')->label('Aktif')->badge(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBankAccounts::route('/'),
            'create' => CreateBankAccount::route('/create'),
            'edit' => EditBankAccount::route('/{record}/edit'),
        ];
    }
}
