<?php

namespace Database\Factories;

use App\Models\Reward;
use App\Models\RewardRedemption;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RewardRedemption>
 */
class RewardRedemptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'reward_id' => Reward::factory(),
            'points_used' => fake()->numberBetween(500, 3000),
            'status' => 'pending',
            'notes' => null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }
}
