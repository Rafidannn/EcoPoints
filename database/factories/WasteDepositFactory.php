<?php

namespace Database\Factories;

use App\Models\DropPoint;
use App\Models\User;
use App\Models\WasteDeposit;
use App\Models\WasteType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WasteDeposit>
 */
class WasteDepositFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'drop_point_id' => DropPoint::factory(),
            'waste_type_id' => WasteType::factory(),
            'weight_kg' => fake()->randomFloat(2, 0.5, 25.0),
            'photo' => null,
            'status' => 'pending',
            'verified_by' => null,
            'verified_at' => null,
            'notes' => null,
        ];
    }

    public function verified(?User $verifier = null): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'verified',
            'verified_by' => $verifier?->id ?? User::factory()->petugas(),
            'verified_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'notes' => 'Sampah kotor atau tercampur bahan berbahaya.',
        ]);
    }
}
