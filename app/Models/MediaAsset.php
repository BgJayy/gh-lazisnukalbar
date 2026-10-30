<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

#[Fillable(['source_path', 'path', 'filename', 'mime', 'size_bytes', 'alt'])]
class MediaAsset extends Model
{
    protected function casts(): array
    {
        return [
            'size_bytes' => 'integer',
        ];
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'cover_media_id');
    }

    public function publicUrl(): string
    {
        return Storage::disk('public')->url($this->path);
    }
}
