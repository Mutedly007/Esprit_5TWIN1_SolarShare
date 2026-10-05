<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_admin_dashboard(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
            'solar_role' => 'rent',
        ]);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_with_role_column_can_access_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'solar_role' => 'lend',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('SolarShare Admin')
            ->assertSee('Dashboard')
            ->assertSee($admin->name);
    }

    public function test_admin_with_solar_role_column_can_access_dashboard(): void
    {
        // Supports setting solar_role = 'admin' directly in phpMyAdmin as well
        $admin = User::factory()->create([
            'role' => 'user',
            'solar_role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('SolarShare Admin');
    }

    public function test_dashboard_redirect_shortcut(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'solar_role' => 'lend',
        ]);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_sees_admin_dashboard_link_in_navbar(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'solar_role' => 'rent',
        ]);

        $this->actingAs($admin)
            ->get(route('home'))
            ->assertOk()
            ->assertSee(route('admin.dashboard'));
    }
}
