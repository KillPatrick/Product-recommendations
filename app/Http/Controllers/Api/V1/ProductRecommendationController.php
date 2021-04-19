<?php

namespace App\Http\Controllers\Api\V1;

use App\Repositories\Interfaces\WeatherConditionInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Product;

class ProductRecommendationController extends Controller
{
    private $weatherData;
    private $limit;
    private $days;

    public function __construct(WeatherConditionInterface $weatherData)
    {
        $this->limit = 2;
        $this->days = 3;
        $this->weatherData = $weatherData;
    }

    /**
     * @param Request $request
     * @param string $city
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function dailyWeatherConditionRecommendations(Request $request, $city)
    {
        $request->validate([
            'limit' => 'integer|min:1|max:5',
            'days' => 'integer|min:1|max:5'
        ]);

        if ($request->has('limit')) {
            $this->limit = $request->limit;
        }

        if ($request->has('days')) {
            $this->days = $request->days;
        }

        $dailyWeatherConditions = $this->weatherData->getDailyWeatherConditions($city, $this->days);
        $recommendations = new Collection();

        foreach ($dailyWeatherConditions['data'] as $date => $dailyWeatherCondition) {
            $cacheName = $this->limit . $city . $date . $dailyWeatherCondition . $this->limit;

            $products = cache()->remember($cacheName, 300, function () use ($dailyWeatherCondition) {
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

        return response()->json(collect([
            'source' => $dailyWeatherConditions['source'],
            'city' => $city,
            'recommendations' => $recommendations,
        ]));
    }
}
