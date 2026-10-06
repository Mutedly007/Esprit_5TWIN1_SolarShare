<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $lender;
    protected User $renter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@solarshare.tn',
            'role' => 'admin',
            'solar_role' => 'admin',
        ]);

        $this->lender = User::factory()->create([
            'name' => 'Moncef Lender',
            'email' => 'moncef@solarshare.tn',
            'role' => 'user',
            'solar_role' => 'lend',
        ]);

        $this->renter = User::factory()->create([
            'name' => 'Nadia Renter',
            'email' => 'nadia@solarshare.tn',
            'role' => 'user',
            'solar_role' => 'rent',
        ]);
    }

    public function test_guests_cannot_access_admin_users(): void
    {
        $this->get(route('admin.users.index'))->assertRedirect(route('login'));
        $this->get(route('admin.users.show', $this->lender))->assertRedirect(route('login'));
    }

    public function test_regular_users_cannot_access_admin_users(): void
    {
        $this->actingAs($this->lender)
            ->get(route('admin.users.index'))
            ->assertForbidden();

        $this->actingAs($this->renter)
            ->get(route('admin.users.show', $this->lender))
            ->assertForbidden();
    }

    public function test_admin_can_view_users_index(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee('Gestion des Utilisateurs')
            ->assertSee('Moncef Lender')
            ->assertSee('moncef@solarshare.tn')
            ->assertSee('Nadia Renter')
            ->assertSee('nadia@solarshare.tn');
    }

    public function test_admin_can_filter_users_by_search_and_role(): void
    {
        // Search by keyword
        $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['search' => 'Moncef']))
            ->assertOk()
            ->assertSee('Moncef Lender')
            ->assertDontSee('Nadia Renter');

        // Filter by role
        $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['solar_role' => 'rent']))
            ->assertOk()
            ->assertSee('Nadia Renter')
            ->assertDontSee('Moncef Lender');
    }

    public function test_admin_can_view_user_details(): void
    {
        $category = Category::create([
            'name' => 'Batteries',
            'slug' => 'batteries',
        ]);

        Equipment::create([
            'user_id' => $this->lender->id,
            'category_id' => $category->id,
            'name' => 'Station Solaire 500W',
            'brand' => 'EcoFlow',
            'daily_rate' => 20.00,
            'is_available' => true,
            'status' => 'approved',
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.users.show', $this->lender))
            ->assertOk()
            ->assertSee('Moncef Lender')
            ->assertSee('moncef@solarshare.tn')
            ->assertSee('Station Solaire 500W');
    }

    public function test_admin_can_update_user_role(): void
    {
        $this->actingAs($this->admin)
            ->patch(route('admin.users.update-role', $this->lender), [
                'solar_role' => 'both',
            ])
            ->assertRedirect();

        $this->assertSame('both', $this->lender->fresh()->solar_role);
    }

    public function test_admin_cannot_delete_self(): void
    {
        $this->actingAs($this->admin)
            ->delete(route('admin.users.destroy', $this->admin))
            ->assertRedirect();

        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    public function test_admin_can_delete_other_user(): void
    {
        $targetUser = User::factory()->create([
            'name' => 'To Delete',
            'email' => 'todelete@solarshare.tn',
        ]);

        $this->actingAs($this->admin)
            ->delete(route('admin.users.destroy', $targetUser))
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseMissing('users', ['id' => $targetUser->id]);
    }
}
