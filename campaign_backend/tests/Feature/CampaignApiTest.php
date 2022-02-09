<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\CampaignCreative;
use Faker\Factory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CampaignApiTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_get_all_campaigns_api()
    {
        $response = $this->get('/api/ad-campaigns', [
            'Accept' => 'application/json',
        ]);

        $response->assertOk();

        $response->assertJsonStructure([
            'status',
            'status_code',
            'data'
        ]);
    }

    public function test_new_campaigns_stored_api()
    {
        $date = date('Y-m-d');

        Storage::fake('public');
        $file = \Illuminate\Http\UploadedFile::fake()->create('/images/campaign/test_image/test-2.jpg');

        $response = $this->post(
            'api/store/ad-campaigns',
            [
                'name' => 'This is an ad for ' . $this->faker->word(),
                'from' => $date,
                'to' => date('Y-m-d', strtotime($date . ' + 1 days')),
                'total_budget' => 5000,
                'daily_budget' => 1000,
                'creatives' => [ $file ]
            ]
        );

        $response->assertCreated();

        $response->assertJsonStructure([
            'status',
            'status_code',
            'data'
        ]);
    }

    public function test_single_campaign_fetch_api()
    {
        $campaign = Campaign::factory()->count(1)->create()->first();
        $faker = Factory::create();

        $image_path = $faker->image('public/images/campaign/creatives',400,300, null, false);

        CampaignCreative::create([
            "ad_campaign_id" => $campaign->id,
            "path" => "campaign/creatives/".$image_path
        ]);

        $response = $this->get("/api/ad-campaigns?campaign_id={$campaign->id}", [
                'Accept' => 'application/json',
            ]);

        $response->assertOk();

        $response->assertJsonStructure([
            'status',
            'status_code',
            'data'
        ]);
    }

    public function test_campaign_update_api()
    {
        $campaign = Campaign::factory()->count(1)->create()->first();
        $faker = Factory::create();

        $image_path = $faker->image('public/images/campaign/creatives',400,300, null, false);

        CampaignCreative::create([
            "ad_campaign_id" => $campaign->id,
            "path" => "campaign/creatives/".$image_path
        ]);


        Storage::fake('public');
        $file = \Illuminate\Http\UploadedFile::fake()->create('/images/campaign/test_image/test-1.jpg');

        $response = $this->post(
            "api/store/ad-campaigns",
            [
                'campaign_id' => $campaign->id,
                'name' => 'This is an ad for ' . $this->faker->word(),
                'from' => $campaign->from,
                'to' => date('Y-m-d', strtotime($campaign->from . ' + 1 days')),
                'total_budget' => 5000,
                'daily_budget' => 1000,
                'creatives' => [ $file ]
            ]
        );

        $response->assertOk();

        $response->assertJsonStructure([
            'status',
            'status_code',
            'data'
        ]);
    }
}
