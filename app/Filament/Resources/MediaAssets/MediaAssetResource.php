<?php

namespace App\Filament\Resources\MediaAssets;

use App\Filament\Resources\MediaAssets\Pages\ListMediaAssets;
use App\Models\MediaAsset;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MediaAssetResource extends Resource
{
    protected static ?string $model = MediaAsset::class;

    protected static ?string $navigationLabel = 'Gambar';

    protected static ?string $modelLabel = 'Gambar';

    protected static ?string $pluralModelLabel = 'Gambar';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static string|\UnitEnum|null $navigationGroup = 'Konten';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'filename';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('path')->label('Gambar')->disk('public')->height(48),
                TextColumn::make('filename')->label('Berkas')->searchable(),
                TextColumn::make('source_path')->label('Asal')->searchable()->toggleable(),
                TextColumn::make('size_bytes')->label('Ukuran')->formatStateUsing(
                    fn (int $state): string => number_format($state / 1024, 0, ',', '.').' KB'
                ),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMediaAssets::route('/'),
        ];
    }
}
