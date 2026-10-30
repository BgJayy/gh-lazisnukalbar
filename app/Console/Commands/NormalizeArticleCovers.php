<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Support\ArticleCover;
use Illuminate\Console\Command;

class NormalizeArticleCovers extends Command
{
    protected $signature = 'articles:normalize-covers';

    protected $description = 'Ubah sampul berita menjadi 1200 × 630 piksel';

    public function handle(): int
    {
        $count = 0;

        Article::query()->orderBy('id')->each(function (Article $article) use (&$count): void {
            if (blank($article->cover_path) || ArticleCover::isStandard($article->cover_path)) {
                return;
            }

            $article->save();
            $count++;
        });

        $this->info($count.' sampul disesuaikan ke '.ArticleCover::WIDTH.' × '.ArticleCover::HEIGHT.'.');

        return self::SUCCESS;
    }
}
