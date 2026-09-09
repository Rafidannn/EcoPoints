<?php

namespace Database\Factories;

use App\Models\PointTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PointTransaction>
 */
class PointTransactionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => 'credit',
            'amount' => fake()->numberBetween(100, 2000),
            'reference_type' => null,
            'reference_id' => null,
            'description' => fake()->sentence(),
        ];
    }

    public function debit(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'debit',
        ]);
    }
}
