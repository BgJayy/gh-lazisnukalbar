<?php

namespace App\Support;

use App\Models\Article;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class WordpressBerita
{
    public static function import(): int
    {
        $path = database_path('data/berita.json');
        $posts = json_decode((string) file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
        $count = 0;

        foreach ($posts as $post) {
            Article::query()->updateOrCreate(
                ['slug' => $post['slug']],
                [
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'] !== '' ? $post['excerpt'] : null,
                    'body' => self::body((string) $post['body']),
                    'category' => $post['category'] ?: 'Berita',
                    'cover_path' => self::libraryPath($post['featured_url'] ?? null),
                    'wp_id' => $post['wp_id'] ?? null,
                    'published_at' => $post['published_at'],
                ],
            );
            $count++;
        }

        return $count;
    }

    public static function body(string $html): string
    {
        $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $html = preg_replace('/\s+(srcset|sizes|width|height|loading|decoding|class|id|style)="[^"]*"/i', '', $html) ?? $html;
        $html = preg_replace("/\s+(srcset|sizes|width|height|loading|decoding|class|id|style)='[^']*'/i", '', $html) ?? $html;
        $html = preg_replace_callback(
            '#https?://[^"\'\s>]+/wp-content/uploads/([^"\'\s>]+)#',
            fn (array $match): string => self::publicUrl(rawurldecode($match[1])) ?? $match[0],
            $html,
        ) ?? $html;
        $html = preg_replace('#<a\b[^>]*>\s*(<img\b[^>]*>)\s*</a>#i', '$1', $html) ?? $html;
        $html = strip_tags($html, '<p><br><strong><b><em><i><ul><ol><li><h2><h3><blockquote><img>');
        $html = preg_replace("/\n{3,}/", "\n\n", $html) ?? $html;

        return trim($html);
    }

    public static function libraryPath(?string $url): ?string
    {
        if (blank($url) || ! str_contains($url, '/wp-content/uploads/')) {
            return null;
        }

        $relative = rawurldecode(Str::after($url, '/wp-content/uploads/'));
        $path = self::existingLibraryFile($relative);

        return $path;
    }

    private static function publicUrl(string $relative): ?string
    {
        $path = self::existingLibraryFile($relative);

        return $path === null ? null : '/storage/'.$path;
    }

    private static function existingLibraryFile(string $relative): ?string
    {
        $relative = ltrim($relative, '/');
        $candidates = [
            $relative,
            preg_replace('/-\d+x\d+(?=\.[A-Za-z0-9]+$)/', '', $relative) ?? $relative,
        ];

        foreach (array_unique($candidates) as $candidate) {
            $path = 'library/'.$candidate;

            if (Storage::disk('public')->exists($path)) {
                return $path;
            }
        }

        return null;
    }
}
