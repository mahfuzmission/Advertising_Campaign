<?php

use Illuminate\Support\Facades\Route;
Use App\Http\Controllers\CampaignController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/ad-campaigns', [CampaignController::class, 'index']);
Route::post('store/ad-campaigns', [CampaignController::class, 'store']);

