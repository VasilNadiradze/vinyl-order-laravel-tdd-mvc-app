<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Scorpions',
            'cost' => 2.5,
            'image' => 'https://vnadiradze.ge/info/laravel/images/vinyl.png',
        ];
    }
}

