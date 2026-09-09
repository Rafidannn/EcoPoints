<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\PointTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PointHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_points_page_with_balance_and_totals(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::USER,
            'points_balance' => 2500,
        ]);

        PointTransaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'credit',
            'amount' => 3000,
            'description' => 'Setor sampah',
        ]);

        PointTransaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'debit',
            'amount' => 500,
            'description' => 'Tukar pulsa',
        ]);

        $response = $this->actingAs($user)->get(route('points.index'));

        $response->assertStatus(200);
        $response->assertSee('Poin Saya & Mutasi Saldo');
        $response->assertSee('2,500'); // balance
        $response->assertSee('+3,000'); // total credit
        $response->assertSee('-500'); // total debit
        $response->assertSee('Setor sampah');
        $response->assertSee('Tukar pulsa');
    }

    public function test_user_can_filter_transactions_by_type(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);

        PointTransaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'credit',
            'amount' => 1500,
            'description' => 'Bonus setoran plastik',
        ]);

        PointTransaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'debit',
            'amount' => 400,
            'description' => 'Voucher belanja',
        ]);

        $creditResponse = $this->actingAs($user)->get(route('points.index', ['type' => 'credit']));
        $creditResponse->assertStatus(200);
        $creditResponse->assertSee('Bonus setoran plastik');
        $creditResponse->assertDontSee('Voucher belanja');

        $debitResponse = $this->actingAs($user)->get(route('points.index', ['type' => 'debit']));
        $debitResponse->assertStatus(200);
        $debitResponse->assertSee('Voucher belanja');
        $debitResponse->assertDontSee('Bonus setoran plastik');
    }

    public function test_user_only_sees_own_transactions(): void
    {
        $user1 = User::factory()->create(['role' => UserRole::USER]);
        $user2 = User::factory()->create(['role' => UserRole::USER]);

        PointTransaction::factory()->create([
            'user_id' => $user1->id,
            'description' => 'Transaksi Rahasia User 1',
        ]);

        $response = $this->actingAs($user2)->get(route('points.index'));
        $response->assertStatus(200);
        $response->assertDontSee('Transaksi Rahasia User 1');
    }
}
