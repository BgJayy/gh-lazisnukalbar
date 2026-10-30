<?php

namespace Database\Seeders;

use App\Enums\BranchType;
use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $pw = Branch::query()->updateOrCreate(
            ['slug' => 'pw-kalbar'],
            [
                'name' => 'PW NU Care-LAZISNU Kalimantan Barat',
                'type' => BranchType::Pw,
                'city_district' => 'Kalimantan Barat',
                'is_active' => true,
            ],
        );

        $branches = [
            'Kota Pontianak' => 'kota-pontianak',
            'Kota Singkawang' => 'kota-singkawang',
            'Kubu Raya' => 'kubu-raya',
            'Mempawah' => 'mempawah',
            'Sambas' => 'sambas',
            'Bengkayang' => 'bengkayang',
            'Landak' => 'landak',
            'Sanggau' => 'sanggau',
            'Sekadau' => 'sekadau',
            'Sintang' => 'sintang',
            'Melawi' => 'melawi',
            'Kapuas Hulu' => 'kapuas-hulu',
            'Ketapang' => 'ketapang',
            'Kayong Utara' => 'kayong-utara',
        ];

        foreach ($branches as $name => $slug) {
            Branch::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => 'PC LAZISNU '.$name,
                    'type' => BranchType::Pc,
                    'parent_id' => $pw->id,
                    'city_district' => $name,
                    'is_active' => true,
                ],
            );
        }
    }
}
