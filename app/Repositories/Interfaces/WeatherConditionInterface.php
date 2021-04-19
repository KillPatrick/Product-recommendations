<?php

namespace App\Repositories\Interfaces;

interface WeatherConditionInterface
{
    public function getDailyWeatherConditions($city, $days);

    public function getWeatherApiData($city);
}
