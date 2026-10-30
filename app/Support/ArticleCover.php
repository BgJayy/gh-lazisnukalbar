<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

final class ArticleCover
{
    public const WIDTH = 1200;

    public const HEIGHT = 630;

    public static function isStandard(string $path): bool
    {
        if (! str_starts_with($path, 'articles/covers/')) {
            return false;
        }

        $absolute = Storage::disk('public')->path($path);

        if (! is_file($absolute)) {
            return false;
        }

        $size = getimagesize($absolute);

        return $size !== false && $size[0] === self::WIDTH && $size[1] === self::HEIGHT;
    }

    public static function store(string $sourcePath, int $articleId): ?string
    {
        $disk = Storage::disk('public');

        if (! $disk->exists($sourcePath)) {
            return null;
        }

        $image = self::load($disk->path($sourcePath));

        if ($image === null) {
            return null;
        }

        $canvas = self::cover($image);
        imagedestroy($image);

        $relative = 'articles/covers/'.$articleId.'.jpg';
        $destination = $disk->path($relative);
        $directory = dirname($destination);

        if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
            imagedestroy($canvas);

            return null;
        }

        $written = imagejpeg($canvas, $destination, 85);
        imagedestroy($canvas);

        if ($written !== true || ! is_file($destination)) {
            return null;
        }

        return $relative;
    }

    private static function load(string $absolute): ?\GdImage
    {
        $info = @getimagesize($absolute);

        if ($info === false) {
            return null;
        }

        $image = match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($absolute),
            IMAGETYPE_PNG => @imagecreatefrompng($absolute),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($absolute) : false,
            IMAGETYPE_GIF => @imagecreatefromgif($absolute),
            default => false,
        };

        if (! $image instanceof \GdImage) {
            return null;
        }

        if ($info[2] === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
            $exif = @exif_read_data($absolute);
            $image = self::orient($image, (int) ($exif['Orientation'] ?? 1));
        }

        return $image;
    }

    private static function orient(\GdImage $image, int $orientation): \GdImage
    {
        $rotated = match ($orientation) {
            3 => imagerotate($image, 180, 0),
            6 => imagerotate($image, -90, 0),
            8 => imagerotate($image, 90, 0),
            default => $image,
        };

        if ($rotated instanceof \GdImage && $rotated !== $image) {
            imagedestroy($image);

            return $rotated;
        }

        return $image;
    }

    private static function cover(\GdImage $image): \GdImage
    {
        $sourceWidth = imagesx($image);
        $sourceHeight = imagesy($image);
        $scale = max(self::WIDTH / $sourceWidth, self::HEIGHT / $sourceHeight);
        $cropWidth = min($sourceWidth, max(1, (int) round(self::WIDTH / $scale)));
        $cropHeight = min($sourceHeight, max(1, (int) round(self::HEIGHT / $scale)));
        $sourceX = (int) max(0, ($sourceWidth - $cropWidth) / 2);
        $sourceY = (int) max(0, ($sourceHeight - $cropHeight) / 2);

        $canvas = imagecreatetruecolor(self::WIDTH, self::HEIGHT);
        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefilledrectangle($canvas, 0, 0, self::WIDTH, self::HEIGHT, $white);
        imagecopyresampled(
            $canvas,
            $image,
            0,
            0,
            $sourceX,
            $sourceY,
            self::WIDTH,
            self::HEIGHT,
            $cropWidth,
            $cropHeight,
        );

        return $canvas;
    }
}
