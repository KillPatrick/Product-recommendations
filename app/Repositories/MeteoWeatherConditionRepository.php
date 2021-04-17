<?php

namespace App\Repositories;

use App\Repositories\Interfaces\WeatherConditionInterface;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp;

class MeteoWeatherConditionRepository implements WeatherConditionInterface
{
    public function getDailyWeatherConditions($city, $days)
    {
        $weatherApiData = $this->getWeatherApiData($city);
        $dailyWeatherConditionsArray = new Collection();

        for ($day = 1; $day <= $days; $day++) {
            $weatherConditionsArray = new Collection();
            $startTime = Carbon::createFromTimeString('8:00')->addDays($day);
            $endTime = Carbon::createFromTimeString('22:00')->addDays($day);
            $forecastDate = '';

            foreach ($weatherApiData['forecastTimestamps'] as $forecastTimestamp) {
                $forecastTimeUtc = Carbon::createFromFormat('Y-m-d H:i:s', $forecastTimestamp['forecastTimeUtc']);

                if ($forecastTimeUtc->between($startTime, $endTime)) {
                    $weatherConditionsArray->push($forecastTimestamp['conditionCode']);
                    $forecastDate = $forecastTimeUtc->format('Y-m-d');
                }

            }

            if ($weatherConditionsArray->count()) {
                $dailyWeatherConditionsArray->put($forecastDate, $weatherConditionsArray->countBy()
                    ->sortDesc()
                    ->keys()
                    ->first()
                );
            }
        }

        return $dailyWeatherConditionsArray;
    }

    public function getWeatherApiData($city)
    {
        return cache()->remember($city, 300, function () use ($city) {
            $weatherApiData = new Collection();

            try {
                $client = new GuzzleHttp\Client();
                $response = $client->request('GET', "https://api.meteo.lt/v1/places/$city/forecasts/long-term");
                $weatherApiData = json_decode($response->getBody()->getContents(), true);
            } catch (ClientException $exception) {
                abort(404, 'Weather conditions api not found');
            }

            return $weatherApiData;
        });
    }
}
