<?php

namespace App\Filament\Resources\Articles;

use App\Filament\Resources\Articles\Pages\CreateArticle;
use App\Filament\Resources\Articles\Pages\EditArticle;
use App\Filament\Resources\Articles\Pages\ListArticles;
use App\Models\Article;
use App\Support\ArticleCover;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationLabel = 'Berita';

    protected static ?string $modelLabel = 'Berita';

    protected static ?string $pluralModelLabel = 'Berita';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static string|\UnitEnum|null $navigationGroup = 'Konten';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')->label('Judul')->required()->maxLength(255)->columnSpanFull(),
                TextInput::make('slug')->label('Slug')->required()->unique(ignoreRecord: true)->maxLength(255),
                TextInput::make('category')->label('Kategori')->default('Berita')->required()->maxLength(100),
                DateTimePicker::make('published_at')->label('Tanggal terbit')->default(now())->required(),
                FileUpload::make('cover_path')
                    ->label('Sampul')
                    ->disk('public')
                    ->directory('articles/uploads')
                    ->image()
                    ->imageAspectRatio(ArticleCover::WIDTH.':'.ArticleCover::HEIGHT)
                    ->automaticallyResizeImagesMode('cover')
                    ->automaticallyResizeImagesToWidth((string) ArticleCover::WIDTH)
                    ->automaticallyResizeImagesToHeight((string) ArticleCover::HEIGHT)
                    ->automaticallyUpscaleImagesWhenResizing()
                    ->helperText('Gambar yang lebih besar atau lebih kecil otomatis disesuaikan menjadi 1200 × 630 piksel.')
                    ->columnSpanFull(),
                Textarea::make('excerpt')->label('Ringkasan')->rows(3)->columnSpanFull(),
                Textarea::make('body')->label('Isi')->required()->rows(12)->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_path')->label('Gambar')->disk('public')->height(48),
                TextColumn::make('title')->label('Judul')->searchable()->wrap(),
                TextColumn::make('published_at')->label('Tanggal')->date('d M Y')->sortable(),
                TextColumn::make('category')->label('Kategori'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->defaultSort('published_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListArticles::route('/'),
            'create' => CreateArticle::route('/create'),
            'edit' => EditArticle::route('/{record}/edit'),
        ];
    }
}
