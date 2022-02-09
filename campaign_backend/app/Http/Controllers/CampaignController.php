<?php

namespace App\Http\Controllers;

use App\Classes\Helper;
use App\Models\Campaign;
use App\Models\CampaignCreative;
use App\Requests\StoreCampaignRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $campaign_id = $request->campaign_id ?? null;

            $campaigns = Campaign::when(isset($campaign_id), function ($query) use ($campaign_id){
                return $query->where('id', $campaign_id);
            })
                ->orderBy('id','desc')
                ->get();

            return Helper::successResponse($campaigns);

        }catch (\Exception $exception){
            Log::emergency("Campaign get api error : ".json_encode($exception->getMessage())." trace : ". json_encode($exception->getTrace()));
            return Helper::errorResponse($exception->getMessage(), Helper::SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCampaignRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCampaignRequest $request)
    {
        try {

            $campaign = (isset($request->campaign_id) && !empty($request->campaign_id)) ? Campaign::find($request->campaign_id) : null;

            if(!empty($campaign))
            {
                DB::beginTransaction();

                $campaign->name = $request->name;
                $campaign->from = Carbon::parse($request->date_from)->toDateString();
                $campaign->to = Carbon::parse($request->date_to)->toDateString();
                $campaign->total_budget = $request->total_budget;
                $campaign->daily_budget = $request->daily_budget;
                $campaign->save();

                if( $request->file('creatives') != null && count($request->file('creatives')))
                {

                    foreach($campaign->creatives as $creative){
                        if(File::exists(public_path('/images')."/".$creative->path))
                        {
                            File::delete(public_path('/images')."/".$creative->path);
                        }
                    }

                    $campaign->creatives->each->delete();

                    foreach ($request->file('creatives') as $index => $creative) {
                        $campaignCreative = new CampaignCreative();
                        $campaignCreative->ad_campaign_id = $campaign->id;
                        $campaignCreative->path = Storage::disk('public')->put('campaign/creatives', $creative);
                        $campaignCreative->save();
                    }
                }

                DB::commit();
                return Helper::successResponse($campaign, Helper::SUCCESS);
            }
            else
            {
                DB::beginTransaction();

                $campaign = new Campaign();
                $campaign->name = $request->name;
                $campaign->from = Carbon::parse($request->date_from)->toDateString();
                $campaign->to = Carbon::parse($request->date_to)->toDateString();
                $campaign->total_budget = $request->total_budget;
                $campaign->daily_budget = $request->daily_budget;
                $campaign->save();

                foreach ($request->file('creatives') as $index => $creative) {
                    $campaignCreative = new CampaignCreative();
                    $campaignCreative->ad_campaign_id = $campaign->id;
                    $campaignCreative->path = Storage::disk('public')->put('campaign/creatives', $creative);
                    $campaignCreative->save();
                }

                DB::commit();
                return Helper::successResponse($campaign, Helper::CREATED);
            }
        }catch (\Exception $exception) {
            DB::rollBack();

            Log::emergency("Campaign store api error : ".json_encode($exception->getMessage())." trace : ". json_encode($exception->getTrace()));
            return Helper::errorResponse($exception->getMessage(), Helper::SERVER_ERROR);
        }
    }

}
