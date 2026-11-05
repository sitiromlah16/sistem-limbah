<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_keeps_session_through_dashboard_redirect(): void
    {
        config(['session.driver' => 'file']);

        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $response = $this->post(route('login.post'), [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertCookie('sistem_limbah_session');

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Pusat validasi produksi', false);
    }

    public function test_all_roles_can_log_in_with_a_fresh_session_cookie(): void
    {
        $roles = [
            'operator' => 'operator.laporan.index',
            'admin' => 'admin.dashboard',
            'manajer' => 'manajer.dashboard',
        ];

        foreach ($roles as $role => $destination) {
            $user = User::factory()->create([
                'email' => "{$role}@example.com",
                'password' => Hash::make('password123'),
                'role' => $role,
            ]);

            $this->get(route('login'))->assertOk();

            $response = $this->post(route('login.post'), [
                'email' => $user->email,
                'password' => 'password123',
            ]);

            $response->assertRedirect(route($destination));
            $response->assertCookie('sistem_limbah_session');
            $this->get(route($destination))->assertOk();

            auth()->logout();
            $this->flushSession();
        }
    }

    public function test_opening_another_login_form_does_not_invalidate_an_existing_form_token(): void
    {
        User::factory()->create([
            'email' => 'operator@example.com',
            'password' => Hash::make('password123'),
            'role' => 'operator',
        ]);

        $this->get(route('login'))->assertOk();
        $this->get(route('login'))->assertOk();

        $this->post(route('login.post'), [
            'email' => 'operator@example.com',
            'password' => 'password123',
        ])->assertRedirect(route('operator.laporan.index'));
    }

    public function test_authenticated_root_redirects_to_each_role_dashboard(): void
    {
        $destinations = [
            'operator' => 'operator.laporan.index',
            'admin' => 'admin.dashboard',
            'manajer' => 'manajer.dashboard',
        ];

        foreach ($destinations as $role => $destination) {
            $user = User::factory()->create(['role' => $role]);

            $this->actingAs($user)
                ->get('/')
                ->assertRedirect(route($destination));
        }
    }
}
