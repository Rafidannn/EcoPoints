<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\DropPoint;
use App\Models\User;
use App\Models\WasteDeposit;
use App\Models\WasteType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WasteDepositTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_deposit_create_form(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $dropPoint = DropPoint::factory()->create();
        $wasteType = WasteType::factory()->create();

        $response = $this->actingAs($user)->get(route('deposits.create'));

        $response->assertStatus(200);
        $response->assertSee('Form Setor Sampah');
        $response->assertSee($dropPoint->name);
        $response->assertSee($wasteType->name);
    }

    public function test_user_can_submit_waste_deposit_with_photo(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['role' => UserRole::USER]);
        $dropPoint = DropPoint::factory()->create();
        $wasteType = WasteType::factory()->create(['points_per_kg' => 500]);

        $photo = UploadedFile::fake()->create('sampah_plastik.jpg', 200, 'image/jpeg');

        $response = $this->actingAs($user)->post(route('deposits.store'), [
            'drop_point_id' => $dropPoint->id,
            'waste_type_id' => $wasteType->id,
            'weight_kg' => 3.5,
            'photo' => $photo,
            'notes' => 'Botol plastik sudah dicuci bersih.',
        ]);

        $deposit = WasteDeposit::first();
        $this->assertNotNull($deposit);
        $this->assertEquals($user->id, $deposit->user_id);
        $this->assertEquals($dropPoint->id, $deposit->drop_point_id);
        $this->assertEquals($wasteType->id, $deposit->waste_type_id);
        $this->assertEquals('3.50', $deposit->weight_kg);
        $this->assertEquals('pending', $deposit->status);
        $this->assertNotNull($deposit->photo);

        Storage::disk('public')->assertExists($deposit->photo);

        $response->assertRedirect(route('deposits.show', $deposit));
    }

    public function test_user_can_view_own_deposits_list_and_detail(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $deposit = WasteDeposit::factory()->create([
            'user_id' => $user->id,
            'weight_kg' => 2.0,
        ]);

        $response = $this->actingAs($user)->get(route('deposits.index'));
        $response->assertStatus(200);
        $response->assertSee('Riwayat Setoran Sampah');
        $response->assertSee(number_format($deposit->weight_kg, 2));

        $detailResponse = $this->actingAs($user)->get(route('deposits.show', $deposit));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Detail Setoran Sampah');
    }

    public function test_user_cannot_view_other_user_deposit(): void
    {
        $user1 = User::factory()->create(['role' => UserRole::USER]);
        $user2 = User::factory()->create(['role' => UserRole::USER]);

        $deposit = WasteDeposit::factory()->create([
            'user_id' => $user1->id,
        ]);

        $response = $this->actingAs($user2)->get(route('deposits.show', $deposit));
        $response->assertStatus(403);
    }
}
