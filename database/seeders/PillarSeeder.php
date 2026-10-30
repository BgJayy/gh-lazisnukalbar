<?php

namespace Database\Seeders;

use App\Models\Pillar;
use App\Support\PillarCatalog;
use Illuminate\Database\Seeder;

class PillarSeeder extends Seeder
{
    public function run(): void
    {
        foreach (PillarCatalog::all() as $index => $pillar) {
            $record = Pillar::query()->where('slug', $pillar['slug'])->first()
                ?? Pillar::query()->where('slug', $pillar['legacy_slug'])->first()
                ?? new Pillar;

            $record->fill([
                'name' => $pillar['name'],
                'slug' => $pillar['slug'],
                'icon' => $pillar['icon'],
                'description' => $pillar['description'],
                'programs' => $pillar['programs'],
                'sort_order' => $index + 1,
            ])->save();
        }
    }
}
