<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminEquipmentTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $lender;
    protected User $renter;
    protected Category $category;
    protected Equipment $equipment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Admin Boss',
            'email' => 'admin@solarshare.tn',
            'role' => 'admin',
            'solar_role' => 'admin',
        ]);

        $this->lender = User::factory()->create([
            'name' => 'Kamel Lender',
            'email' => 'kamel@solarshare.tn',
            'role' => 'user',
            'solar_role' => 'lend',
        ]);

        $this->renter = User::factory()->create([
            'name' => 'Sonia Renter',
            'email' => 'sonia@solarshare.tn',
            'role' => 'user',
            'solar_role' => 'rent',
        ]);

        $this->category = Category::create([
            'name' => 'Panneaux Photovoltaïques',
            'slug' => 'panneaux-photovoltaiques',
            'description' => 'Panneaux monocristallins et polycristallins',
        ]);

        $this->equipment = Equipment::create([
            'user_id' => $this->lender->id,
            'category_id' => $this->category->id,
            'name' => 'Panneau Solaire Pliable 400W',
            'brand' => 'SunPower',
            'description' => 'Panneau haut rendement étanche IP67.',
            'power_watts' => 400,
            'capacity_wh' => null,
            'daily_rate' => 25.00,
            'deposit_amount' => 300.00,
            'is_available' => true,
            'status' => 'pending',
        ]);
    }

    public function test_guests_cannot_access_admin_equipment(): void
    {
        $this->get(route('admin.equipment.index'))->assertRedirect(route('login'));
        $this->get(route('admin.equipment.show', $this->equipment))->assertRedirect(route('login'));
    }

    public function test_regular_users_cannot_access_admin_equipment(): void
    {
        $this->actingAs($this->lender)
            ->get(route('admin.equipment.index'))
            ->assertForbidden();

        $this->actingAs($this->renter)
            ->get(route('admin.equipment.show', $this->equipment))
            ->assertForbidden();
    }

    public function test_admin_can_view_equipment_index(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.equipment.index'))
            ->assertOk()
            ->assertSee('Inventaire & Gestion des Équipements')
            ->assertSee('Panneau Solaire Pliable 400W')
            ->assertSee('SunPower')
            ->assertSee('Kamel Lender')
            ->assertSee('Non traité');
    }

    public function test_admin_can_filter_equipment_by_search_and_category(): void
    {
        $otherEquipment = Equipment::create([
            'user_id' => $this->lender->id,
            'category_id' => $this->category->id,
            'name' => 'Batterie Nomade Jackery 1000',
            'brand' => 'Jackery',
            'daily_rate' => 35.00,
            'deposit_amount' => 400.00,
            'is_available' => true,
            'status' => 'approved',
        ]);

        // Search match
        $this->actingAs($this->admin)
            ->get(route('admin.equipment.index', ['search' => 'SunPower']))
            ->assertOk()
            ->assertSee('Panneau Solaire Pliable 400W')
            ->assertDontSee('Batterie Nomade Jackery 1000');

        // Status match
        $this->actingAs($this->admin)
            ->get(route('admin.equipment.index', ['status' => 'approved']))
            ->assertOk()
            ->assertSee('Batterie Nomade Jackery 1000')
            ->assertDontSee('Panneau Solaire Pliable 400W');
    }

    public function test_admin_can_view_equipment_details(): void
    {
        // Add a rental to test rental history display
        Rental::create([
            'user_id' => $this->renter->id,
            'equipment_id' => $this->equipment->id,
            'start_date' => now()->addDay()->format('Y-m-d'),
            'end_date' => now()->addDays(3)->format('Y-m-d'),
            'total_price' => 75.00,
            'status' => 'confirmed',
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.equipment.show', $this->equipment))
            ->assertOk()
            ->assertSee('Panneau Solaire Pliable 400W')
            ->assertSee('SunPower')
            ->assertSee('Kamel Lender')
            ->assertSee('kamel@solarshare.tn')
            ->assertSee('400 W')
            ->assertSee('25.00')
            ->assertSee('Sonia Renter');
    }

    public function test_admin_can_approve_equipment(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.equipment.approve', $this->equipment))
            ->assertRedirect();

        $this->assertSame('approved', $this->equipment->fresh()->status);
    }

    public function test_admin_can_reject_equipment(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.equipment.reject', $this->equipment))
            ->assertRedirect();

        $this->assertSame('rejected', $this->equipment->fresh()->status);
    }

    public function test_admin_can_toggle_equipment_availability(): void
    {
        $this->assertTrue($this->equipment->is_available);

        $this->actingAs($this->admin)
            ->post(route('admin.equipment.toggle-availability', $this->equipment))
            ->assertRedirect();

        $this->assertFalse($this->equipment->fresh()->is_available);

        $this->actingAs($this->admin)
            ->post(route('admin.equipment.toggle-availability', $this->equipment))
            ->assertRedirect();

        $this->assertTrue($this->equipment->fresh()->is_available);
    }

    public function test_admin_can_delete_equipment(): void
    {
        $this->actingAs($this->admin)
            ->delete(route('admin.equipment.destroy', $this->equipment))
            ->assertRedirect(route('admin.equipment.index'));

        $this->assertDatabaseMissing('equipment', ['id' => $this->equipment->id]);
    }
}
