<?php

namespace Database\Factories;

use App\Models\Pillar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pillar>
 */
class PillarFactory extends Factory
{
    protected $model = Pillar::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'sort_order' => 1,
        ];
    }
}
