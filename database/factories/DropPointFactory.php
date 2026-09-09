<?php

namespace Database\Factories;

use App\Models\DropPoint;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DropPoint>
 */
class DropPointFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Drop Point '.fake()->city(),
            'address' => fake()->address(),
            'latitude' => fake()->latitude(-6.3, -6.1),
            'longitude' => fake()->longitude(106.7, 106.9),
            'is_active' => true,
        ];
    }
}
