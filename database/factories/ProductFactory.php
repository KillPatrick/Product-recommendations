<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->sentence(rand(1, 2));
        $sku = strtoupper(substr($name, 0, 2)) . '-' . rand(1, 100);

        return [
            'name' => $name,
            'description' => $this->faker->sentence(rand(1, 6)),
            'price' => rand(30, 3000),
            'sku' => $sku,
        ];
    }
}
