<?php

namespace App\Filament\Resources\Donations;

use App\Enums\DonationChannel;
use App\Enums\FundBook;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Filament\Resources\Donations\Pages\CreateDonation;
use App\Filament\Resources\Donations\Pages\EditDonation;
use App\Filament\Resources\Donations\Pages\ListDonations;
use App\Models\Donation;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DonationResource extends Resource
{
    protected static ?string $model = Donation::class;

    protected static ?string $navigationLabel = 'Donasi';

    protected static ?string $modelLabel = 'Donasi';

    protected static ?string $pluralModelLabel = 'Donasi';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|\UnitEnum|null $navigationGroup = 'Keuangan';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'invoice_number';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('donor_name')->label('Nama donatur')->required()->maxLength(255),
                TextInput::make('donor_phone')->label('WhatsApp')->maxLength(30),
                TextInput::make('donor_email')->label('Email')->email()->maxLength(255),
                Toggle::make('is_anonymous')->label('Sembunyikan nama'),
                Select::make('branch_id')->label('Cabang')->relationship('branch', 'name')->searchable()->preload()->required(),
                Select::make('channel')->label('Kanal')->options(DonationChannel::options())->required()->native(false)->live(),
                Select::make('fund_book')->label('Buku kas')->options(FundBook::options())->required()->native(false),
                Select::make('campaign_id')
                    ->label('Kampanye')
                    ->relationship('campaign', 'title')
                    ->searchable()
                    ->preload()
                    ->visible(fn (Get $get): bool => $get('channel') === DonationChannel::Campaign->value),
                TextInput::make('amount')->label('Nominal')->numeric()->prefix('Rp')->required()->minValue(1),
                Select::make('payment_method')->label('Metode')->options(PaymentMethod::options())->default(PaymentMethod::ManualTransfer->value)->required()->native(false),
                Select::make('payment_status')->label('Status')->options(PaymentStatus::options())->default(PaymentStatus::Pending->value)->required()->native(false),
                FileUpload::make('proof_of_transfer')->label('Bukti transfer')->disk('local')->directory('donation-proofs')->image()->columnSpanFull(),
                Textarea::make('prayer_message')->label('Doa')->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')->label('Invoice')->searchable(),
                TextColumn::make('donor_name')->label('Donatur')->searchable(),
                TextColumn::make('branch.name')->label('Cabang'),
                TextColumn::make('fund_book')->label('Buku kas')->badge(),
                TextColumn::make('channel')->label('Kanal')->badge(),
                TextColumn::make('amount')->label('Nominal')->money('IDR'),
                TextColumn::make('payment_status')->label('Status')->badge(),
            ])
            ->filters([
                SelectFilter::make('payment_status')->label('Status')->options(PaymentStatus::options()),
                SelectFilter::make('fund_book')->label('Buku kas')->options(FundBook::options()),
            ])
            ->recordActions([
                Action::make('publicStatus')
                    ->label('Lihat status publik')
                    ->url(fn (Donation $record): string => route('donasi.show', $record->invoice_number), true),
                EditAction::make(),
                Action::make('approve')
                    ->label('Setujui')
                    ->requiresConfirmation()
                    ->visible(fn (Donation $record): bool => $record->payment_status === PaymentStatus::Pending && (auth()->user()?->can('approve', $record) ?? false))
                    ->action(function (Donation $record): void {
                        $record->update([
                            'payment_status' => PaymentStatus::Paid,
                        ]);
                    }),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->visibleTo();
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDonations::route('/'),
            'create' => CreateDonation::route('/create'),
            'edit' => EditDonation::route('/{record}/edit'),
        ];
    }
}
