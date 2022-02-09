<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\CampaignCreative;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Campaign::factory()
            ->count(50)
            ->create()
            ->each(function($campaign){

                $faker = Factory::create();
                $image_path = $faker->image('public/images/campaign/creatives',400,300, null, false);

                CampaignCreative::create([
                    "ad_campaign_id" => $campaign->id,
                    "path" => "campaign/creatives/".$image_path
                ]);
            })
        ;
    }
}
