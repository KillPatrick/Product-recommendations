<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\WeatherConditionInterface;
use App\Repositories\MeteoWeatherConditionRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(WeatherConditionInterface::class, MeteoWeatherConditionRepository::class);
    }
}
