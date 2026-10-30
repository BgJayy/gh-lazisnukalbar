<?php

namespace App\Models;

use App\Support\ArticleCover;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable(['slug', 'title', 'excerpt', 'body', 'category', 'cover_path', 'wp_id', 'published_at'])]
class Article extends Model
{
    protected static function booted(): void
    {
        static::saving(function (Article $article): void {
            if (blank($article->slug) && filled($article->title)) {
                $article->slug = Str::slug($article->title);
            }
        });

        static::saved(function (Article $article): void {
            $path = $article->cover_path;
            $standard = 'articles/covers/'.$article->id.'.jpg';

            if (blank($path)) {
                Storage::disk('public')->delete($standard);

                return;
            }

            if (ArticleCover::isStandard($path)) {
                return;
            }

            $stored = ArticleCover::store($path, $article->id);

            if ($stored === null) {
                if ($path !== $standard && ArticleCover::isStandard($standard)) {
                    self::rememberCover($article, $standard);
                }

                return;
            }

            if ($stored === $path) {
                return;
            }

            if (str_starts_with($path, 'articles/uploads/')) {
                Storage::disk('public')->delete($path);
            }

            self::rememberCover($article, $stored);
        });
    }

    private static function rememberCover(Article $article, string $path): void
    {
        if ($article->cover_path === $path) {
            return;
        }

        $article->newQuery()->whereKey($article->getKey())->update(['cover_path' => $path]);
        $article->cover_path = $path;
    }

    protected function casts(): array
    {
        return [
            'wp_id' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    public function dateLabel(): string
    {
        return $this->published_at?->locale('id')->translatedFormat('j F Y') ?? '';
    }

    public function coverUrl(): ?string
    {
        $disk = Storage::disk('public');
        $path = $this->cover_path;
        $standard = 'articles/covers/'.$this->id.'.jpg';

        if (blank($path) || ! $disk->exists($path)) {
            $path = $disk->exists($standard) ? $standard : null;
        }

        if (blank($path)) {
            return null;
        }

        return url($disk->url($path));
    }

    /**
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeLatestPublished(Builder $query): Builder
    {
        return $query->orderByDesc('published_at')->orderByDesc('id');
    }
}
