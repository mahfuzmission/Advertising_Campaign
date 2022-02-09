<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignCreative extends Model
{
    use HasFactory;

    protected $table = "ad_campaign_creatives";

    protected $fillable = ["ad_campaign_id", "path"];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return env('APP_URL').'/images/'.$this->path;
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class,"");
    }

}
