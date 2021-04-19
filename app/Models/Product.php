<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function getPriceAttribute($price)
    {
        return $price / 100;
    }

    public function weatherConditions()
    {
        return $this->belongsToMany('App\Models\WeatherCondition');
    }
}
