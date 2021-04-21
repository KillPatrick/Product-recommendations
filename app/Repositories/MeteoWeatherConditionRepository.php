<?php

namespace App\Repositories;

use App\Repositories\Interfaces\WeatherConditionInterface;
use App\Http\Utilities\ExternalApi;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use RuntimeException;

class MeteoWeatherConditionRepository implements WeatherConditionInterface
{
    /**
     * @param $city
     * @param $days
     * @return Collection
     */
    public function getDailyWeatherConditions($city, $days)
    {
        $weatherApiData = $this->getWeatherApiData($city);

        if (isset($weatherApiData['error'])) {
            return $weatherApiData;
        }

        $dailyWeatherConditions = new Collection();

        for ($day = 1; $day <= $days; $day++) {
            $weatherConditions = new Collection();
            $startTime = Carbon::createFromTimeString('8:00')->addDays($day);
            $endTime = Carbon::createFromTimeString('22:00')->addDays($day);
            $forecastDate = '';

            try {
                foreach ($weatherApiData['forecastTimestamps'] as $forecastTimestamp) {
                    $forecastTimeUtc = Carbon::createFromFormat('Y-m-d H:i:s', $forecastTimestamp['forecastTimeUtc']);

                    if ($forecastTimeUtc->between($startTime, $endTime)) {
                        $forecastDate = $forecastTimeUtc->format('Y-m-d');
                        $weatherConditions->push($forecastTimestamp['conditionCode']);
                    }
                }
            } catch(RuntimeException $exception){
                throw $exception;
            }


            if ($weatherConditions->count()) {
                $dailyWeatherConditions->put($forecastDate, $weatherConditions->countBy()
                    ->sortDesc()
                    ->keys()
                    ->first()
                );
            }
        }

        return collect([
            'source' => 'LHMT',
            'data' => $dailyWeatherConditions,
        ]);
    }

    public function getWeatherApiData($city)
    {
        $url = "https://api.meteo.lt/v1/places/$city/forecasts/long-term";
        $weatherApiData = cache()->get($city);

        if (!$weatherApiData) {
            $externalApi = new ExternalApi(new Client(), $url);
            $weatherApiData = (array) json_decode($externalApi->get(), true);

            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new RuntimeException('Unable to parse response into JSON: ' . json_last_error());
            }

            if (isset($weatherApiData['error'])) {
                return ($weatherApiData);
            }

            cache()->put($city, $weatherApiData, 300);
        }

        return $weatherApiData;
    }
}
