<?php

namespace App\Support;

final class PublicHtml
{
    public static function clean(?string $html): string
    {
        $html = self::emphasis((string) $html);
        $html = strip_tags($html, '<p><br><strong><b><em><i><ul><ol><li><h2><h3><blockquote><img><a>');
        $html = preg_replace('/\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html) ?? $html;
        $html = preg_replace('/<((?:p|br|strong|b|em|i|ul|ol|li|h2|h3|blockquote))\b[^>]*>/i', '<$1>', $html) ?? $html;

        $html = preg_replace_callback('/<a\b([^>]*)>/i', function (array $match): string {
            $url = self::safeUrl($match[1]);

            return $url === null ? '<a>' : '<a href="'.e($url).'">';
        }, $html) ?? $html;

        $html = preg_replace_callback('/<img\b([^>]*)>/i', function (array $match): string {
            $url = self::safeUrl($match[1]);

            return $url === null ? '' : '<img src="'.e($url).'" alt="">';
        }, $html) ?? $html;

        return $html;
    }

    private static function emphasis(string $html): string
    {
        $html = preg_replace('/\*\*([^*\n]+)\*\*/', '<strong>$1</strong>', $html) ?? $html;

        return preg_replace('/\*([^*\n]+)\*/', '<strong>$1</strong>', $html) ?? $html;
    }

    private static function safeUrl(string $attributes): ?string
    {
        if (! preg_match('/\b(?:href|src)\s*=\s*("|\')(.*?)\1/i', $attributes, $match)) {
            return null;
        }

        $url = trim($match[2]);

        if (preg_match('#^(https?:)?//#i', $url) || str_starts_with($url, '/')) {
            return $url;
        }

        return null;
    }
}
