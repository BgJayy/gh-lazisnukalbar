<?php

namespace App\Filament\Resources\Pillars;

use App\Filament\Resources\Pillars\Pages\CreatePillar;
use App\Filament\Resources\Pillars\Pages\EditPillar;
use App\Filament\Resources\Pillars\Pages\ListPillars;
use App\Models\Pillar;
use App\Support\PillarCatalog;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PillarResource extends Resource
{
    protected static ?string $model = Pillar::class;

    protected static ?string $navigationLabel = 'Pilar';

    protected static ?string $modelLabel = 'Pilar';

    protected static ?string $pluralModelLabel = 'Pilar';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static string|\UnitEnum|null $navigationGroup = 'Program';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->label('Nama')->required()->maxLength(255),
                TextInput::make('slug')->label('Slug')->required()->unique(ignoreRecord: true)->maxLength(255),
                Select::make('icon')->label('Ikon')->options(PillarCatalog::icons())->native(false)->required(),
                TextInput::make('sort_order')->label('Urutan')->numeric()->default(0)->required(),
                Textarea::make('description')->label('Deskripsi')->columnSpanFull(),
                TagsInput::make('programs')
                    ->label('Program')
                    ->placeholder('Tambah program')
                    ->helperText('Daftar ini tampil di halaman pilar. Kampanye memilih pilar ini.')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_order')->label('Urutan'),
                TextColumn::make('name')->label('Nama')->searchable(),
                TextColumn::make('icon')->label('Ikon')->formatStateUsing(fn (?string $state): string => PillarCatalog::icons()[$state] ?? '—'),
                TextColumn::make('campaigns_count')->label('Kampanye'),
            ])
            ->defaultSort('sort_order')
            ->recordActions([
                EditAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount('campaigns');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPillars::route('/'),
            'create' => CreatePillar::route('/create'),
            'edit' => EditPillar::route('/{record}/edit'),
        ];
    }
}
