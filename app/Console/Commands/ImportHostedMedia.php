<?php

namespace App\Console\Commands;

use App\Models\MediaAsset;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ImportHostedMedia extends Command
{
    protected $signature = 'media:import-library';

    protected $description = 'Catat gambar di storage/app/public/library ke tabel media_assets';

    public function handle(): int
    {
        $root = rtrim((string) config('filesystems.disks.public.root'), '/').'/library';

        if (! is_dir($root)) {
            $this->error('Folder library belum ada.');

            return self::FAILURE;
        }

        $count = 0;

        foreach (File::allFiles($root) as $file) {
            $mime = mime_content_type($file->getPathname()) ?: '';

            if (! str_starts_with($mime, 'image/')) {
                continue;
            }

            $relative = str_replace('\\', '/', $file->getRelativePathname());

            MediaAsset::query()->updateOrCreate(
                ['source_path' => $relative],
                [
                    'path' => 'library/'.$relative,
                    'filename' => $file->getFilename(),
                    'mime' => $mime,
                    'size_bytes' => $file->getSize(),
                ],
            );

            $count++;
        }

        $this->info($count.' gambar tercatat.');

        return self::SUCCESS;
    }
}
