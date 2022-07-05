<?php

namespace Database\Factories;

use App\Models\Price;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Price>
 */
class PriceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'timestamp' => $this->faker->date(),
            'price' => $this->faker->randomFloat(max: 1),
            'source' => 'BITTREX',
        ];
    }
}
