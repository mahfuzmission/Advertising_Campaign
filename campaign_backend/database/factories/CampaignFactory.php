<?php

namespace Database\Factories;

use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CampaignFactory extends Factory
{

    protected $model = Campaign::class;

    public function definition()
    {
        $daily_budget = mt_rand(0, 5000);
        $day = mt_rand(1, 30);
        $budget = $day * $daily_budget;

        return [
            "name" => 'This is an ad for ' . $this->faker->word(),
            "from" => Carbon::now()->format('Y-m-d'),
            "to" => Carbon::now()->addDays($day)->format('Y-m-d'),
            "total_budget" => $budget,
            "daily_budget" => $daily_budget,
        ];
    }

}
