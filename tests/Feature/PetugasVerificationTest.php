<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\DropPoint;
use App\Models\PointTransaction;
use App\Models\User;
use App\Models\WasteDeposit;
use App\Models\WasteType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetugasVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_petugas_can_view_deposits_queue(): void
    {
        $petugas = User::factory()->petugas()->create();
        $deposit = WasteDeposit::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($petugas)->get(route('petugas.deposits.index'));

        $response->assertStatus(200);
        $response->assertSee('Antrean Verifikasi Setoran');
        $response->assertSee($deposit->user->name);
    }

    public function test_petugas_can_verify_deposit_and_credit_points(): void
    {
        $petugas = User::factory()->petugas()->create();
        $nasabah = User::factory()->create([
            'role' => UserRole::USER,
            'points_balance' => 100,
        ]);

        $wasteType = WasteType::factory()->create([
            'points_per_kg' => 500,
        ]);

        $deposit = WasteDeposit::factory()->create([
            'user_id' => $nasabah->id,
            'waste_type_id' => $wasteType->id,
            'weight_kg' => 2.00,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($petugas)->post(route('petugas.deposits.verify', $deposit), [
            'actual_weight_kg' => 2.50, // adjusted from 2.0 to 2.5
            'notes' => 'Timbangan fisik pas, kondisi bersih.',
        ]);

        $response->assertRedirect(route('petugas.deposits.index'));

        // Refresh models
        $deposit->refresh();
        $nasabah->refresh();

        $this->assertEquals('verified', $deposit->status);
        $this->assertEquals('2.50', $deposit->weight_kg);
        $this->assertEquals($petugas->id, $deposit->verified_by);
        $this->assertNotNull($deposit->verified_at);

        // Points earned = 2.50 * 500 = 1250
        // New balance = 100 + 1250 = 1350
        $this->assertEquals(1350, $nasabah->points_balance);

        // Transaction record
        $transaction = PointTransaction::where('user_id', $nasabah->id)
            ->where('reference_type', WasteDeposit::class)
            ->where('reference_id', $deposit->id)
            ->first();

        $this->assertNotNull($transaction);
        $this->assertEquals('credit', $transaction->type);
        $this->assertEquals(1250, $transaction->amount);

        // Notification received
        $this->assertCount(1, $nasabah->notifications);
    }

    public function test_petugas_can_reject_deposit_with_reason(): void
    {
        $petugas = User::factory()->petugas()->create();
        $nasabah = User::factory()->create([
            'role' => UserRole::USER,
            'points_balance' => 200,
        ]);

        $deposit = WasteDeposit::factory()->create([
            'user_id' => $nasabah->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($petugas)->post(route('petugas.deposits.reject', $deposit), [
            'rejection_reason' => 'Sampah tercampur limbah basah berbahaya.',
        ]);

        $response->assertRedirect(route('petugas.deposits.index'));

        $deposit->refresh();
        $nasabah->refresh();

        $this->assertEquals('rejected', $deposit->status);
        $this->assertEquals('Sampah tercampur limbah basah berbahaya.', $deposit->notes);
        $this->assertEquals(200, $nasabah->points_balance); // Points unchanged
        $this->assertEquals(0, PointTransaction::count());

        // Rejection notification
        $this->assertCount(1, $nasabah->notifications);
    }

    public function test_regular_user_cannot_verify_or_reject(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $deposit = WasteDeposit::factory()->create(['status' => 'pending']);

        $this->actingAs($user)->post(route('petugas.deposits.verify', $deposit), [
            'actual_weight_kg' => 2.0,
        ])->assertStatus(403);

        $this->actingAs($user)->post(route('petugas.deposits.reject', $deposit), [
            'rejection_reason' => 'Unauthorized rejection',
        ])->assertStatus(403);
    }
}
