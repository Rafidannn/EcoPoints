<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login_from_all_dashboards(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/petugas/dashboard')->assertRedirect('/login');
        $this->get('/admin/dashboard')->assertRedirect('/login');
    }

    public function test_regular_user_can_access_user_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::USER,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Nasabah');
        $response->assertSee('Saldo Poin Anda');
    }

    public function test_regular_user_cannot_access_petugas_or_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::USER,
        ]);

        $this->actingAs($user)->get('/petugas/dashboard')->assertStatus(403);
        $this->actingAs($user)->get('/admin/dashboard')->assertStatus(403);
    }

    public function test_petugas_can_access_petugas_dashboard(): void
    {
        $petugas = User::factory()->petugas()->create();

        $response = $this->actingAs($petugas)->get('/petugas/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Petugas Drop Point');
    }

    public function test_petugas_cannot_access_user_or_admin_dashboard(): void
    {
        $petugas = User::factory()->petugas()->create();

        $this->actingAs($petugas)->get('/dashboard')->assertStatus(403);
        $this->actingAs($petugas)->get('/admin/dashboard')->assertStatus(403);
    }

    public function test_admin_can_access_admin_and_petugas_dashboards(): void
    {
        $admin = User::factory()->admin()->create();

        $adminResponse = $this->actingAs($admin)->get('/admin/dashboard');
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('Dashboard Administrator');

        $petugasResponse = $this->actingAs($admin)->get('/petugas/dashboard');
        $petugasResponse->assertStatus(200);
    }

    public function test_admin_cannot_access_user_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/dashboard')->assertStatus(403);
    }

    public function test_registered_user_defaults_to_user_role_and_zero_points(): void
    {
        $response = $this->post('/register', [
            'name' => 'Ahmad Baru',
            'email' => 'ahmad@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        $user = User::where('email', 'ahmad@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame(UserRole::USER, $user->role);
        $this->assertSame(0, $user->points_balance);
        $this->assertTrue($user->isUser());
        $this->assertFalse($user->isAdmin());
        $this->assertFalse($user->isPetugas());
    }

    public function test_login_redirects_user_based_on_role(): void
    {
        // Admin
        $admin = User::factory()->admin()->create([
            'email' => 'admin_test@ecopoints.test',
            'password' => bcrypt('password'),
        ]);

        $responseAdmin = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);
        $responseAdmin->assertRedirect(route('admin.dashboard', absolute: false));
        $this->post('/logout');

        // Petugas
        $petugas = User::factory()->petugas()->create([
            'email' => 'petugas_test@ecopoints.test',
            'password' => bcrypt('password'),
        ]);

        $responsePetugas = $this->post('/login', [
            'email' => $petugas->email,
            'password' => 'password',
        ]);
        $responsePetugas->assertRedirect(route('petugas.dashboard', absolute: false));
        $this->post('/logout');

        // User
        $user = User::factory()->create([
            'email' => 'user_test@ecopoints.test',
            'password' => bcrypt('password'),
        ]);

        $responseUser = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $responseUser->assertRedirect(route('dashboard', absolute: false));
    }
}
