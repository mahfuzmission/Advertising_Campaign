<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "ad_campaigns";

    protected $with = [
        'creatives',
    ];

    public function creatives()
    {
        return $this->hasMany(CampaignCreative::class,"ad_campaign_id", "id");
    }
}
