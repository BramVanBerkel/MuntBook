<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Block>
 */
class BlockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'hash' => $this->faker->sha256(),
            'confirmations' => $this->faker->numberBetween(0, 100),
            'strippedsize' => $this->faker->numberBetween(1, 100),
            'validated' => 1,
            'size' => $this->faker->numberBetween(1, 100),
            'weight' => $this->faker->numberBetween(1, 100),
            'version' => $this->faker->numberBetween(2, 3),
            'merkleroot' => $this->faker->sha256(),
            'witness_version' => $this->faker->randomElement([null, 536870912]),
            'witness_time' => $this->faker->date(),
            'pow_time' => $this->faker->date(),
            'witness_merkleroot' => $this->faker->sha256(),
            'time' => $this->faker->date(),
            'nonce' => $this->faker->numberBetween(),
            'pre_nonce' => $this->faker->numberBetween(),
            'post_nonce' => $this->faker->numberBetween(),
            'bits' => $this->faker->bothify('#?#???#?'),
            'difficulty' => $this->faker->randomFloat(8),
            'hashrate' => $this->faker->randomFloat(4),
            'chainwork' => $this->faker->numberBetween(),
            'previousblockhash' => $this->faker->sha256(),
            'created_at' => $this->faker->date(),
        ];
    }
}
