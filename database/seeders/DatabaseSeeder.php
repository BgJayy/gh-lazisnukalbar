<?php

namespace Database\Seeders;

use App\Support\WordpressBerita;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            BranchSeeder::class,
            PillarSeeder::class,
            SettingSeeder::class,
            StaffUserSeeder::class,
        ]);

        WordpressBerita::import();
    }
}
