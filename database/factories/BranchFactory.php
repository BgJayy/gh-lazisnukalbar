<?php

namespace Database\Factories;

use App\Enums\BranchType;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Branch>
 */
class BranchFactory extends Factory
{
    protected $model = Branch::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->company(),
            'type' => BranchType::Pc,
            'city_district' => fake()->city(),
            'is_active' => true,
        ];
    }
}
