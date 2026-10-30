<?php

namespace App\Filament\Resources\Campaigns;

use App\Enums\CampaignStatus;
use App\Enums\FundBook;
use App\Filament\Resources\Campaigns\Pages\CreateCampaign;
use App\Filament\Resources\Campaigns\Pages\EditCampaign;
use App\Filament\Resources\Campaigns\Pages\ListCampaigns;
use App\Models\Campaign;
use App\Models\MediaAsset;
use App\Support\BranchAccess;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CampaignResource extends Resource
{
    protected static ?string $model = Campaign::class;

    protected static ?string $navigationLabel = 'Kampanye';

    protected static ?string $modelLabel = 'Kampanye';

    protected static ?string $pluralModelLabel = 'Kampanye';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static string|\UnitEnum|null $navigationGroup = 'Program';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')->label('Judul')->required()->maxLength(255)->columnSpanFull(),
                TextInput::make('slug')->label('Slug')->required()->unique(ignoreRecord: true)->maxLength(255),
                Select::make('branch_id')
                    ->label('Cabang')
                    ->relationship('branch', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->visible(fn (): bool => ! BranchAccess::limitsToBranch()),
                Select::make('pillar_id')
                    ->label('Pilar')
                    ->relationship('pillar', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('Lima pilar program NU Care. Nama yang tampil di situs mengikuti data pilar ini.'),
                Select::make('cover_media_id')
                    ->label('Gambar sampul')
                    ->relationship('cover', 'filename')
                    ->getOptionLabelFromRecordUsing(fn (MediaAsset $record): string => $record->filename)
                    ->searchable()
                    ->preload()
                    ->placeholder('Tanpa gambar'),
                Select::make('fund_book')->label('Buku kas')->options(FundBook::options())->required()->native(false),
                Select::make('status')
                    ->label('Status')
                    ->options(CampaignStatus::options())
                    ->default(CampaignStatus::Draft->value)
                    ->required()
                    ->native(false)
                    ->helperText('Hanya status Tayang yang muncul di beranda dan halaman kampanye.'),
                TextInput::make('target_amount')->label('Target')->numeric()->prefix('Rp')->required()->minValue(0),
                DatePicker::make('start_date')->label('Mulai'),
                DatePicker::make('end_date')->label('Selesai'),
                Toggle::make('accepts_zakat')->label('Menerima zakat'),
                Toggle::make('is_urgent')->label('Mendesak'),
                Textarea::make('story_content')->label('Cerita')->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover.path')->label('Gambar')->disk('public')->height(40),
                TextColumn::make('title')->label('Judul')->searchable(),
                TextColumn::make('branch.name')->label('Cabang'),
                TextColumn::make('pillar.name')->label('Pilar'),
                TextColumn::make('fund_book')->label('Buku kas')->badge(),
                TextColumn::make('status')->label('Status')->badge(),
                TextColumn::make('collected_amount')->label('Terkumpul')->money('IDR'),
                TextColumn::make('target_amount')->label('Target')->money('IDR'),
            ])
            ->recordActions([
                Action::make('site')
                    ->label('Lihat di situs')
                    ->url(fn (Campaign $record): string => route('campaign.show', $record->slug), true)
                    ->visible(fn (Campaign $record): bool => $record->status === CampaignStatus::Published),
                EditAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->visibleTo()->with('cover');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCampaigns::route('/'),
            'create' => CreateCampaign::route('/create'),
            'edit' => EditCampaign::route('/{record}/edit'),
        ];
    }
}
