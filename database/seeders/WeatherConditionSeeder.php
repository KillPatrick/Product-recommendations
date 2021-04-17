<?php

namespace Database\Seeders;

use App\Models\WeatherCondition;
use Illuminate\Database\Seeder;

class WeatherConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $meteoWeatherConditions = [
            'clear',
            'isolated-clouds',
            'scattered-clouds',
            'overcast',
            'light-rain',
            'moderate-rain',
            'heavy-rain',
            'sleet',
            'light-snow',
            'moderate-snow',
            'heavy-snow',
            'fog',
            'na',
        ];

        foreach ($meteoWeatherConditions as $meteoWeatherCondition) {
            WeatherCondition::create(['name' => $meteoWeatherCondition]);
        }
    }
}
