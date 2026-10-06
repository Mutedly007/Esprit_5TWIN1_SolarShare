<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\EnergyKit;
use App\Models\Equipment;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RentalRoleRestrictionTest extends TestCase
{
    use RefreshDatabase;

    protected User $lenderUser;
    protected User $renterUser;
    protected User $bothUser;
    protected Category $category;
    protected Equipment $equipment;
    protected EnergyKit $kit;

    protected function setUp(): void
    {
        parent::setUp();

        $this->lenderUser = User::factory()->create([
            'name' => 'Foued Lender',
            'email' => 'lender@solarshare.tn',
            'role' => 'user',
            'solar_role' => 'lend',
        ]);

        $this->renterUser = User::factory()->create([
            'name' => 'Sami Renter',
            'email' => 'renter@solarshare.tn',
            'role' => 'user',
            'solar_role' => 'rent',
        ]);

        $this->bothUser = User::factory()->create([
            'name' => 'Kamel Both',
            'email' => 'both@solarshare.tn',
            'role' => 'user',
            'solar_role' => 'both',
        ]);

        $this->category = Category::create([
            'name' => 'Générateurs solaires',
            'slug' => 'generateurs-solaires',
            'description' => 'Batteries et stations électriques portables',
        ]);

        // Equipment owned by a separate provider
        $provider = User::factory()->create(['solar_role' => 'lend']);
        $this->equipment = Equipment::create([
            'user_id' => $provider->id,
            'category_id' => $this->category->id,
            'name' => 'EcoFlow Delta 2 Max',
            'brand' => 'EcoFlow',
            'model' => 'Delta 2 Max 2048Wh',
            'daily_rate' => 45.00,
            'deposit_amount' => 500.00,
            'is_available' => true,
            'status' => 'approved',
        ]);

        $this->kit = EnergyKit::create([
            'user_id' => $provider->id,
            'title' => 'Pack Autonomie Chantier',
            'slug' => 'pack-autonomie-chantier',
            'description' => 'Pack solaire portable complet',
            'discount_percentage' => 15,
            'is_active' => true,
        ]);
        $this->kit->equipment()->attach($this->equipment->id, ['quantity' => 1]);
    }

    public function test_user_role_helper_methods(): void
    {
        // Lender role: can lend, CANNOT rent/reserve
        $this->assertTrue($this->lenderUser->isLender());
        $this->assertFalse($this->lenderUser->isRenter());
        $this->assertFalse($this->lenderUser->canReserveEquipment());

        // Renter role: CANNOT lend, can rent/reserve
        $this->assertFalse($this->renterUser->isLender());
        $this->assertTrue($this->renterUser->isRenter());
        $this->assertTrue($this->renterUser->canReserveEquipment());

        // Both role: can both lend and rent
        $this->assertTrue($this->bothUser->isLender());
        $this->assertTrue($this->bothUser->isRenter());
        $this->assertTrue($this->bothUser->canReserveEquipment());
    }

    public function test_user_with_lend_role_cannot_access_rental_creation(): void
    {
        $this->actingAs($this->lenderUser)
            ->get(route('rentals.create', ['equipment_id' => $this->equipment->id]))
            ->assertForbidden();
    }

    public function test_user_with_lend_role_cannot_store_rental(): void
    {
        $startDate = now()->addDays(2)->format('Y-m-d');
        $endDate = now()->addDays(5)->format('Y-m-d');

        $this->actingAs($this->lenderUser)
            ->post(route('rentals.store'), [
                'equipment_id' => $this->equipment->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'renter_notes' => 'Test reservation by lend role',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('rentals', [
            'user_id' => $this->lenderUser->id,
            'equipment_id' => $this->equipment->id,
        ]);
    }

    public function test_user_with_lend_role_cannot_reserve_energy_kit(): void
    {
        $this->actingAs($this->lenderUser)
            ->post(route('energy-kits.reserve', $this->kit), [
                'start_date' => now()->addDays(1)->format('Y-m-d'),
                'end_date' => now()->addDays(3)->format('Y-m-d'),
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('energy_kit_reservations', [
            'user_id' => $this->lenderUser->id,
            'energy_kit_id' => $this->kit->id,
        ]);
    }

    public function test_user_with_lend_role_sees_warning_and_disabled_button_on_marketplace(): void
    {
        $this->actingAs($this->lenderUser)
            ->get(route('marketplace.show', $this->equipment))
            ->assertOk()
            ->assertSee('Réservation non autorisée')
            ->assertSee('Votre compte a le rôle')
            ->assertSee('Réservation réservée aux emprunteurs')
            ->assertDontSee(route('rentals.create', ['equipment_id' => $this->equipment->id]));
    }

    public function test_user_with_rent_role_can_access_and_create_rental(): void
    {
        $startDate = now()->addDays(1)->format('Y-m-d');
        $endDate = now()->addDays(4)->format('Y-m-d');

        // Can access create page
        $this->actingAs($this->renterUser)
            ->get(route('rentals.create', ['equipment_id' => $this->equipment->id]))
            ->assertOk()
            ->assertSee('Réserver un équipement')
            ->assertSee($this->equipment->name);

        // Can submit reservation
        $response = $this->actingAs($this->renterUser)
            ->post(route('rentals.store'), [
                'equipment_id' => $this->equipment->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'renter_notes' => 'Besoin pour week-end camping',
            ]);

        $this->assertDatabaseHas('rentals', [
            'user_id' => $this->renterUser->id,
            'equipment_id' => $this->equipment->id,
            'status' => 'pending',
        ]);

        $rental = Rental::where('user_id', $this->renterUser->id)->first();
        $this->assertNotNull($rental);
        $response->assertRedirect(route('rentals.show', $rental));
    }

    public function test_user_with_both_role_can_lend_and_rent(): void
    {
        // Can access lender space
        $this->actingAs($this->bothUser)
            ->get(route('lender.dashboard'))
            ->assertOk();

        // Can also create equipment
        $this->actingAs($this->bothUser)
            ->get(route('equipment.create'))
            ->assertOk();

        // AND can access rental creation
        $this->actingAs($this->bothUser)
            ->get(route('rentals.create', ['equipment_id' => $this->equipment->id]))
            ->assertOk();

        // AND can submit rental reservation
        $startDate = now()->addDays(3)->format('Y-m-d');
        $endDate = now()->addDays(6)->format('Y-m-d');

        $this->actingAs($this->bothUser)
            ->post(route('rentals.store'), [
                'equipment_id' => $this->equipment->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('rentals', [
            'user_id' => $this->bothUser->id,
            'equipment_id' => $this->equipment->id,
        ]);
    }
}
