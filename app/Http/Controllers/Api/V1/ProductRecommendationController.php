<?php

namespace App\Http\Controllers\Api\V1;

use App\Repositories\Interfaces\WeatherConditionInterface;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Product;

class ProductRecommendationController extends Controller
{
    private $weatherData;
    private $limit = 2;
    private $days = 3;

    public function __construct(WeatherConditionInterface $weatherData)
    {
        $this->weatherData = $weatherData;
    }

    public function dailyWeatherConditionRecommendations($city)
    {
        $dailyWeatherConditions = $this->weatherData->getDailyWeatherConditions($city, $this->days);
        $dailyRecommendations = collect(['city' => $city]);
        $recommendations = new Collection();

        foreach ($dailyWeatherConditions as $date => $dailyWeatherCondition) {
            $products = cache()->remember($dailyWeatherCondition . $date, 300, function () use ($dailyWeatherCondition) {
                return Product::whereHas('weatherConditions', function ($query) use ($dailyWeatherCondition) {
                    $query->where('name', $dailyWeatherCondition);
                })->inRandomOrder()->limit($this->limit)->get(['name', 'sku', 'price']);
            });

            $recommendations->push([
                'weather_conditions' => $dailyWeatherCondition,
                'date' => $date,
                'products' => $products,
            ]);

        }

        $dailyRecommendations->put('recommendations', $recommendations);
        return response()->json($dailyRecommendations);
    }
}
