<?php

namespace Database\Factories;

use App\Models\Block;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Block>
 */
class BlockFactory extends Factory
{
    public function definition(): array
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
            'witness_version' => $this->faker->randomElement([null, 536_870_912]),
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
