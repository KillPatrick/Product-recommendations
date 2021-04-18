<?php

namespace Tests\Feature;

use App\Repositories\Interfaces\WeatherConditionInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_products_recommended_api()
    {
        $this->get('/api/products/recommended/kaunas')->assertStatus(200);
    }
}
