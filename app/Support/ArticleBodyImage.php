<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class ArticleBodyImage
{
    public const MAX_WIDTH = 1200;

    public const QUALITY = 82;

    public const DIRECTORY = 'articles/body';

    public static function store(UploadedFile $file): ?string
    {
        $image = self::load($file->getRealPath());

        if ($image === null) {
            return $file->store(self::DIRECTORY, 'public') ?: null;
        }

        $resized = self::resize($image);

        if ($resized !== $image) {
            imagedestroy($image);
        }

        $relative = self::DIRECTORY.'/'.Str::random(32).'.jpg';
        $destination = Storage::disk('public')->path($relative);
        $directory = dirname($destination);

        if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
            imagedestroy($resized);

            return null;
        }

        $written = imagejpeg($resized, $destination, self::QUALITY);
        imagedestroy($resized);

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

        return self::flatten($image);
    }

    private static function flatten(\GdImage $image): \GdImage
    {
        $width = imagesx($image);
        $height = imagesy($image);

        $canvas = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefilledrectangle($canvas, 0, 0, $width, $height, $white);
        imagecopy($canvas, $image, 0, 0, 0, 0, $width, $height);
        imagedestroy($image);

        return $canvas;
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

    private static function resize(\GdImage $image): \GdImage
    {
        $sourceWidth = imagesx($image);
        $sourceHeight = imagesy($image);

        if ($sourceWidth <= self::MAX_WIDTH) {
            return $image;
        }

        $newWidth = self::MAX_WIDTH;
        $newHeight = max(1, (int) round($sourceHeight * $newWidth / $sourceWidth));

        $canvas = imagecreatetruecolor($newWidth, $newHeight);
        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefilledrectangle($canvas, 0, 0, $newWidth, $newHeight, $white);
        imagecopyresampled(
            $canvas,
            $image,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $sourceWidth,
            $sourceHeight,
        );

        return $canvas;
    }
}
