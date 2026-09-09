<?php

namespace Database\Factories;

use App\Models\WasteType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WasteType>
 */
class WasteTypeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'unit_price_per_kg' => fake()->randomFloat(2, 1000, 10000),
            'points_per_kg' => fake()->numberBetween(100, 1000),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
