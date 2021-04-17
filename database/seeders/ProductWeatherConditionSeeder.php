<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\WeatherCondition;
use Illuminate\Database\Seeder;


class ProductWeatherConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::all()->each(function ($product) {
            WeatherCondition::all()
                ->random(rand(1, rand(1, 2)))
                ->each(function ($weatherCondition) use ($product) {
                    $product->weatherConditions()->attach($weatherCondition->id);
                });
        });
    }
}
