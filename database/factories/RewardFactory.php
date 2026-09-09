<?php

namespace Database\Factories;

use App\Models\Reward;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reward>
 */
class RewardFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'point_cost' => fake()->numberBetween(500, 5000),
            'stock' => fake()->numberBetween(5, 50),
            'image' => null,
            'is_active' => true,
        ];
    }
}
