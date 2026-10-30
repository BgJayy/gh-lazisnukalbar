<?php

namespace App\Models;

use Database\Factories\PillarFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable(['name', 'slug', 'icon', 'description', 'programs', 'sort_order'])]
class Pillar extends Model
{
    /** @use HasFactory<PillarFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'programs' => 'array',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Pillar $pillar): void {
            if (blank($pillar->slug) && filled($pillar->name)) {
                $pillar->slug = Str::slug($pillar->name);
            }
        });
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }
}
