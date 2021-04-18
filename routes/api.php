<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ProductRecommendationController;

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

Route::get('products/recommended/{city}', [ProductRecommendationController::class, 'dailyWeatherConditionRecommendations'])
    ->where(['city' => '^[a-z]{3,20}$']);
