<?php

namespace Database\Factories;

use App\Enums\CampaignStatus;
use App\Enums\FundBook;
use App\Models\Branch;
use App\Models\Campaign;
use App\Models\Pillar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Campaign>
 */
class CampaignFactory extends Factory
{
    protected $model = Campaign::class;

    public function definition(): array
    {
        return [
            'branch_id' => Branch::factory(),
            'pillar_id' => Pillar::factory(),
            'title' => fake()->unique()->sentence(3),
            'fund_book' => FundBook::InfakSedekah,
            'accepts_zakat' => false,
            'target_amount' => 1_000_000,
            'status' => CampaignStatus::Draft,
            'is_urgent' => false,
        ];
    }
}
