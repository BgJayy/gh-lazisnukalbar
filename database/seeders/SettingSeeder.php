<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::query()->firstOrCreate(
            ['key' => Setting::GOLD_PRICE],
            ['value' => null],
        );
    }
}
