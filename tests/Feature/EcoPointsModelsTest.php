<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\DropPoint;
use App\Models\PointTransaction;
use App\Models\Reward;
use App\Models\RewardRedemption;
use App\Models\User;
use App\Models\WasteDeposit;
use App\Models\WasteType;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EcoPointsModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_waste_deposit_relationships_and_casts(): void
    {
        $user = User::factory()->create();
        $petugas = User::factory()->petugas()->create();
        $dropPoint = DropPoint::factory()->create();
        $wasteType = WasteType::factory()->create([
            'points_per_kg' => 500,
        ]);

        $deposit = WasteDeposit::factory()->create([
            'user_id' => $user->id,
            'drop_point_id' => $dropPoint->id,
            'waste_type_id' => $wasteType->id,
            'weight_kg' => 2.50,
            'status' => 'verified',
            'verified_by' => $petugas->id,
            'verified_at' => now(),
        ]);

        $pointTx = PointTransaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'credit',
            'amount' => 1250,
            'reference_type' => WasteDeposit::class,
            'reference_id' => $deposit->id,
            'description' => 'Setor sampah plastik 2.5 kg',
        ]);

        $this->assertEquals($user->id, $deposit->user->id);
        $this->assertEquals($dropPoint->id, $deposit->dropPoint->id);
        $this->assertEquals($wasteType->id, $deposit->wasteType->id);
        $this->assertEquals($petugas->id, $deposit->verifier->id);
        $this->assertEquals('2.50', $deposit->weight_kg);

        // Reverse relationships
        $this->assertTrue($user->wasteDeposits->contains($deposit));
        $this->assertTrue($petugas->verifiedDeposits->contains($deposit));
        $this->assertTrue($dropPoint->wasteDeposits->contains($deposit));
        $this->assertTrue($wasteType->wasteDeposits->contains($deposit));

        // Polymorphic reference
        $this->assertInstanceOf(WasteDeposit::class, $pointTx->reference);
        $this->assertEquals($deposit->id, $pointTx->reference->id);
    }

    public function test_reward_and_redemption_relationships(): void
    {
        $user = User::factory()->create(['points_balance' => 5000]);
        $reward = Reward::factory()->create([
            'point_cost' => 1000,
            'stock' => 10,
        ]);

        $redemption = RewardRedemption::factory()->create([
            'user_id' => $user->id,
            'reward_id' => $reward->id,
            'points_used' => 1000,
            'status' => 'pending',
        ]);

        $pointTx = PointTransaction::factory()->debit()->create([
            'user_id' => $user->id,
            'amount' => 1000,
            'reference_type' => RewardRedemption::class,
            'reference_id' => $redemption->id,
            'description' => 'Penukaran reward: '.$reward->name,
        ]);

        $this->assertEquals($user->id, $redemption->user->id);
        $this->assertEquals($reward->id, $redemption->reward->id);
        $this->assertTrue($reward->redemptions->contains($redemption));
        $this->assertTrue($user->rewardRedemptions->contains($redemption));
        $this->assertInstanceOf(RewardRedemption::class, $pointTx->reference);
        $this->assertEquals($redemption->id, $pointTx->reference->id);
    }

    public function test_database_seeder_populates_initial_data(): void
    {
        $this->seed(DatabaseSeeder::class);

        // Check seeded users
        $admin = User::where('email', 'admin@ecopoints.test')->first();
        $this->assertNotNull($admin);
        $this->assertSame(UserRole::ADMIN, $admin->role);

        $petugas = User::where('email', 'petugas@ecopoints.test')->first();
        $this->assertNotNull($petugas);
        $this->assertSame(UserRole::PETUGAS, $petugas->role);

        $budi = User::where('email', 'budi@ecopoints.test')->first();
        $this->assertNotNull($budi);
        $this->assertSame(UserRole::USER, $budi->role);
        $this->assertEquals(1500, $budi->points_balance);

        // Check seeded waste types
        $this->assertGreaterThanOrEqual(6, WasteType::count());
        $this->assertNotNull(WasteType::where('name', 'like', '%Plastik%')->first());
        $this->assertNotNull(WasteType::where('name', 'like', '%Kertas%')->first());
        $this->assertNotNull(WasteType::where('name', 'like', '%Logam%')->first());

        // Check seeded drop points
        $this->assertGreaterThanOrEqual(4, DropPoint::count());
    }
}
