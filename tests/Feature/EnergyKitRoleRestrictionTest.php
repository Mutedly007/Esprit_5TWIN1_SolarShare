<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\EnergyKit;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnergyKitRoleRestrictionTest extends TestCase
{
    use RefreshDatabase;

    protected User $lenderUser;
    protected User $otherLender;
    protected User $renterUser;
    protected User $bothUser;
    protected User $adminUser;
    protected Category $category;
    protected Equipment $lenderEquipment;
    protected Equipment $otherEquipment;
    protected Equipment $pendingEquipment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->lenderUser = User::factory()->create([
            'name' => 'Lender One',
            'email' => 'lender1@solarshare.tn',
            'role' => 'user',
            'solar_role' => 'lend',
        ]);

        $this->otherLender = User::factory()->create([
            'name' => 'Lender Two',
            'email' => 'lender2@solarshare.tn',
            'role' => 'user',
            'solar_role' => 'lend',
        ]);

        $this->renterUser = User::factory()->create([
            'name' => 'Renter User',
            'email' => 'renter@solarshare.tn',
            'role' => 'user',
            'solar_role' => 'rent',
        ]);

        $this->bothUser = User::factory()->create([
            'name' => 'Both User',
            'email' => 'both@solarshare.tn',
            'role' => 'user',
            'solar_role' => 'both',
        ]);

        $this->adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@solarshare.tn',
            'role' => 'admin',
            'solar_role' => 'admin',
        ]);

        $this->category = Category::create([
            'name' => 'Panneaux solaires',
            'slug' => 'panneaux-solaires',
            'description' => 'Panneaux solaires rigides et pliables',
        ]);

        // 1. Approved equipment owned by lenderUser
        $this->lenderEquipment = Equipment::create([
            'user_id' => $this->lenderUser->id,
            'category_id' => $this->category->id,
            'name' => 'Panneau Solaire Lender1 400W',
            'brand' => 'SunPower',
            'daily_rate' => 25.00,
            'deposit_amount' => 150.00,
            'status' => 'approved',
            'is_available' => true,
        ]);

        // 2. Approved equipment owned by otherLender
        $this->otherEquipment = Equipment::create([
            'user_id' => $this->otherLender->id,
            'category_id' => $this->category->id,
            'name' => 'Panneau Solaire Lender2 300W',
            'brand' => 'EcoFlow',
            'daily_rate' => 20.00,
            'deposit_amount' => 120.00,
            'status' => 'approved',
            'is_available' => true,
        ]);

        // 3. Pending equipment owned by lenderUser (not yet approved)
        $this->pendingEquipment = Equipment::create([
            'user_id' => $this->lenderUser->id,
            'category_id' => $this->category->id,
            'name' => 'Panneau Solaire Pending 100W',
            'brand' => 'Generic',
            'daily_rate' => 10.00,
            'deposit_amount' => 50.00,
            'status' => 'pending',
            'is_available' => true,
        ]);
    }

    public function test_renter_cannot_access_energy_kit_creation(): void
    {
        $response = $this->actingAs($this->renterUser)
            ->get(route('energy-kits.create'));

        $response->assertStatus(403);
    }

    public function test_renter_cannot_store_energy_kit(): void
    {
        $response = $this->actingAs($this->renterUser)
            ->post(route('energy-kits.store'), [
                'title' => 'Pack Interdit Renter',
                'target_use' => 'vanlife',
                'discount_percentage' => 15,
                'items' => [
                    [
                        'equipment_id' => $this->lenderEquipment->id,
                        'quantity' => 1,
                    ],
                ],
            ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('energy_kits', ['title' => 'Pack Interdit Renter']);
    }

    public function test_renter_does_not_see_create_button_on_kits_index(): void
    {
        $response = $this->actingAs($this->renterUser)
            ->get(route('energy-kits.index'));

        $response->assertOk();
        $response->assertDontSee(route('energy-kits.create'));
    }

    public function test_lender_can_access_create_and_only_sees_own_approved_equipment(): void
    {
        $response = $this->actingAs($this->lenderUser)
            ->get(route('energy-kits.create'));

        $response->assertOk();
        // Should see their own approved equipment
        $response->assertSee('Panneau Solaire Lender1 400W');
        // Should NOT see another lender's equipment
        $response->assertDontSee('Panneau Solaire Lender2 300W');
        // Should NOT see pending equipment
        $response->assertDontSee('Panneau Solaire Pending 100W');
    }

    public function test_lender_cannot_create_pack_with_another_lenders_equipment(): void
    {
        $response = $this->actingAs($this->lenderUser)
            ->from(route('energy-kits.create'))
            ->post(route('energy-kits.store'), [
                'title' => 'Pack Vol Equipement',
                'target_use' => 'vanlife',
                'discount_percentage' => 10,
                'items' => [
                    [
                        'equipment_id' => $this->otherEquipment->id, // belongs to Lender 2!
                        'quantity' => 1,
                    ],
                ],
            ]);

        $response->assertRedirect(route('energy-kits.create'));
        $response->assertSessionHasErrors(['items']);
        $this->assertDatabaseMissing('energy_kits', ['title' => 'Pack Vol Equipement']);
    }

    public function test_lender_can_create_pack_with_own_approved_equipment(): void
    {
        $response = $this->actingAs($this->lenderUser)
            ->post(route('energy-kits.store'), [
                'title' => 'Mon Pack Solaire Vanlife',
                'description' => 'Un super pack pour voyager',
                'target_use' => 'vanlife',
                'discount_percentage' => 15,
                'items' => [
                    [
                        'equipment_id' => $this->lenderEquipment->id,
                        'quantity' => 2,
                        'options' => 'Câble 5m inclus',
                    ],
                ],
            ]);

        $kit = EnergyKit::where('title', 'Mon Pack Solaire Vanlife')->first();
        $this->assertNotNull($kit);
        $this->assertEquals($this->lenderUser->id, $kit->user_id);
        $this->assertEquals(15.0, (float) $kit->discount_percentage);

        $response->assertRedirect(route('energy-kits.show', $kit));
        $this->assertDatabaseHas('energy_kit_equipment', [
            'energy_kit_id' => $kit->id,
            'equipment_id' => $this->lenderEquipment->id,
            'quantity' => 2,
            'options' => 'Câble 5m inclus',
        ]);
    }

    public function test_both_role_can_create_pack_with_own_equipment(): void
    {
        $bothEquipment = Equipment::create([
            'user_id' => $this->bothUser->id,
            'category_id' => $this->category->id,
            'name' => 'Panneau Both 250W',
            'brand' => 'SunPower',
            'daily_rate' => 18.00,
            'deposit_amount' => 100.00,
            'status' => 'approved',
            'is_available' => true,
        ]);

        $response = $this->actingAs($this->bothUser)
            ->post(route('energy-kits.store'), [
                'title' => 'Pack Solaire Mixte Both',
                'target_use' => 'camping',
                'discount_percentage' => 10,
                'items' => [
                    [
                        'equipment_id' => $bothEquipment->id,
                        'quantity' => 1,
                    ],
                ],
            ]);

        $kit = EnergyKit::where('title', 'Pack Solaire Mixte Both')->first();
        $this->assertNotNull($kit);
        $this->assertEquals($this->bothUser->id, $kit->user_id);
        $response->assertRedirect(route('energy-kits.show', $kit));
    }

    public function test_admin_can_create_pack_with_any_approved_equipment(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('energy-kits.create'));

        $response->assertOk();
        // Admin sees both approved equipments
        $response->assertSee('Panneau Solaire Lender1 400W');
        $response->assertSee('Panneau Solaire Lender2 300W');

        // Admin can bundle another lender's equipment
        $storeResponse = $this->actingAs($this->adminUser)
            ->post(route('energy-kits.store'), [
                'title' => 'Pack Admin Global',
                'target_use' => 'chantier',
                'discount_percentage' => 20,
                'items' => [
                    [
                        'equipment_id' => $this->otherEquipment->id,
                        'quantity' => 1,
                    ],
                ],
            ]);

        $kit = EnergyKit::where('title', 'Pack Admin Global')->first();
        $this->assertNotNull($kit);
        $this->assertEquals($this->adminUser->id, $kit->user_id);
        $storeResponse->assertRedirect(route('energy-kits.show', $kit));
    }

    public function test_lender_cannot_edit_or_delete_another_lenders_kit(): void
    {
        $kit = EnergyKit::create([
            'user_id' => $this->otherLender->id,
            'title' => 'Pack de Lender 2',
            'target_use' => 'camping',
            'discount_percentage' => 10,
            'is_active' => true,
        ]);

        // Attempt edit
        $responseEdit = $this->actingAs($this->lenderUser)
            ->get(route('energy-kits.edit', $kit));
        $responseEdit->assertStatus(403);

        // Attempt update
        $responseUpdate = $this->actingAs($this->lenderUser)
            ->put(route('energy-kits.update', $kit), [
                'title' => 'Pack Piraté',
                'target_use' => 'camping',
                'discount_percentage' => 10,
                'items' => [
                    ['equipment_id' => $this->lenderEquipment->id, 'quantity' => 1],
                ],
            ]);
        $responseUpdate->assertStatus(403);

        // Attempt delete
        $responseDelete = $this->actingAs($this->lenderUser)
            ->delete(route('energy-kits.destroy', $kit));
        $responseDelete->assertStatus(403);

        $this->assertDatabaseHas('energy_kits', ['id' => $kit->id]);
    }
}
