<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LenderModuleTest extends TestCase
{
    use RefreshDatabase;

    protected User $lender;
    protected User $otherLender;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->lender = User::factory()->create([
            'name' => 'Foued Ben Salah',
            'email' => 'foued@solarshare.tn',
            'role' => 'user',
            'solar_role' => 'lend',
        ]);

        $this->otherLender = User::factory()->create([
            'name' => 'Amira Trabelsi',
            'email' => 'amira@solarshare.tn',
            'role' => 'user',
            'solar_role' => 'lend',
        ]);

        $this->category = Category::create([
            'name' => 'Panneaux solaires portables',
            'slug' => 'panneaux-solaires-portables',
            'description' => 'Panneaux pliables et rigides pour camping et nomades',
        ]);
    }

    public function test_guests_cannot_access_lender_space(): void
    {
        $this->get(route('lender.dashboard'))->assertRedirect(route('login'));
        $this->get(route('categories.index'))->assertRedirect(route('login'));
        $this->get(route('equipment.index'))->assertRedirect(route('login'));
        $this->get(route('equipment.create'))->assertRedirect(route('login'));
    }

    public function test_authenticated_lender_can_access_dashboard(): void
    {
        $this->actingAs($this->lender)
            ->get(route('lender.dashboard'))
            ->assertOk()
            ->assertSee('Bonjour, Foued Ben Salah')
            ->assertSee('Panneaux solaires portables')
            ->assertSee('Mes Équipements');
    }

    public function test_lender_can_view_categories_list(): void
    {
        $this->actingAs($this->lender)
            ->get(route('categories.index'))
            ->assertOk()
            ->assertSee('Panneaux solaires portables')
            ->assertSee('panneaux-solaires-portables')
            ->assertSee('Add Category');
    }

    public function test_lender_can_create_category(): void
    {
        $response = $this->actingAs($this->lender)
            ->post(route('categories.store'), [
                'name' => 'Stations d\'énergie / Batteries',
                'slug' => 'batteries-solaires',
                'description' => 'Batteries LiFePO4 haute capacité',
            ]);

        $response->assertRedirect(route('categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'name' => 'Stations d\'énergie / Batteries',
            'slug' => 'batteries-solaires',
        ]);
    }

    public function test_category_creation_auto_generates_slug_if_blank(): void
    {
        $this->actingAs($this->lender)
            ->post(route('categories.store'), [
                'name' => 'Micro-éoliennes Domestiques',
                'slug' => '',
                'description' => 'Éoliennes de toit et jardin',
            ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Micro-éoliennes Domestiques',
            'slug' => 'micro-eoliennes-domestiques',
        ]);
    }

    public function test_lender_can_edit_and_update_category(): void
    {
        $this->actingAs($this->lender)
            ->get(route('categories.edit', $this->category))
            ->assertOk()
            ->assertSee($this->category->name);

        $response = $this->actingAs($this->lender)
            ->put(route('categories.update', $this->category), [
                'name' => 'Panneaux Solaires Haute Puissance',
                'slug' => 'panneaux-solaires-hp',
                'description' => 'Description mise à jour',
            ]);

        $response->assertRedirect(route('categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'id' => $this->category->id,
            'name' => 'Panneaux Solaires Haute Puissance',
            'slug' => 'panneaux-solaires-hp',
        ]);
    }

    public function test_lender_can_delete_category(): void
    {
        $category = Category::create([
            'name' => 'Catégorie Temporaire',
            'slug' => 'categorie-temporaire',
        ]);

        $response = $this->actingAs($this->lender)
            ->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_equipment_creation_requires_category(): void
    {
        $response = $this->actingAs($this->lender)
            ->post(route('equipment.store'), [
                'name' => 'Panneau 200W',
                'brand' => 'EcoFlow',
                'daily_rate' => 15.00,
                'deposit_amount' => 100.00,
                // Missing category_id
            ]);

        $response->assertSessionHasErrors('category_id');
    }

    public function test_newly_created_equipment_automatically_has_pending_status_and_is_displayed_as_non_traite(): void
    {
        $response = $this->actingAs($this->lender)
            ->post(route('equipment.store'), [
                'name' => 'Panneau solaire pliable 200W',
                'category_id' => $this->category->id,
                'brand' => 'EcoFlow',
                'description' => 'Panneau monocristallin étanche IP68',
                'power_watts' => 200,
                'capacity_wh' => null,
                'daily_rate' => 18.00,
                'deposit_amount' => 120.00,
                'is_available' => true,
            ]);

        $response->assertRedirect(route('equipment.index'))
            ->assertSessionHas('success');

        $equipment = Equipment::where('name', 'Panneau solaire pliable 200W')->first();
        $this->assertNotNull($equipment);
        $this->assertEquals($this->lender->id, $equipment->user_id);
        $this->assertEquals($this->category->id, $equipment->category_id);
        // Requirement 3: status = pending
        $this->assertEquals('pending', $equipment->status);
        // Requirement 3: displayed as "Non traité"
        $this->assertEquals('Non traité', $equipment->status_label);
    }

    public function test_lender_can_upload_equipment_image(): void
    {
        Storage::fake('public');

        $imageFile = UploadedFile::fake()->create('solar-panel.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($this->lender)
            ->post(route('equipment.store'), [
                'name' => 'Panneau avec Image 200W',
                'category_id' => $this->category->id,
                'brand' => 'EcoFlow',
                'daily_rate' => 20.00,
                'deposit_amount' => 100.00,
                'image' => $imageFile,
            ]);

        $response->assertRedirect(route('equipment.index'))
            ->assertSessionHas('success');

        $equipment = Equipment::where('name', 'Panneau avec Image 200W')->first();
        $this->assertNotNull($equipment);
        $this->assertNotNull($equipment->image);

        Storage::disk('public')->assertExists($equipment->image);
        $this->assertNotNull($equipment->image_url);
    }

    public function test_pending_equipment_remains_visible_in_lender_dashboard_and_equipment_page(): void
    {
        $equipment = Equipment::create([
            'user_id' => $this->lender->id,
            'category_id' => $this->category->id,
            'name' => 'Solar Panel 200W EcoFlow',
            'brand' => 'EcoFlow',
            'description' => 'Test pending equipment visibility',
            'power_watts' => 200,
            'daily_rate' => 15.00,
            'deposit_amount' => 100.00,
            'is_available' => true,
            'status' => 'pending',
        ]);

        // Visible in dashboard
        $this->actingAs($this->lender)
            ->get(route('lender.dashboard'))
            ->assertOk()
            ->assertSee('Solar Panel 200W EcoFlow')
            ->assertSee('Non traité');

        // Visible in equipment index
        $this->actingAs($this->lender)
            ->get(route('equipment.index'))
            ->assertOk()
            ->assertSee('Solar Panel 200W EcoFlow')
            ->assertSee('Non traité')
            ->assertSee('15.00 DT');
    }

    public function test_lender_only_sees_their_own_equipment(): void
    {
        $myEquipment = Equipment::create([
            'user_id' => $this->lender->id,
            'category_id' => $this->category->id,
            'name' => 'Mon Panneau 200W',
            'brand' => 'EcoFlow',
            'daily_rate' => 15.00,
            'deposit_amount' => 100.00,
            'status' => 'pending',
        ]);

        $otherEquipment = Equipment::create([
            'user_id' => $this->otherLender->id,
            'category_id' => $this->category->id,
            'name' => 'Panneau d\'Amira 400W',
            'brand' => 'Renogy',
            'daily_rate' => 30.00,
            'deposit_amount' => 200.00,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->lender)
            ->get(route('equipment.index'));

        $response->assertOk()
            ->assertSee('Mon Panneau 200W')
            ->assertDontSee('Panneau d\'Amira 400W');
    }

    public function test_lender_cannot_view_another_users_equipment(): void
    {
        $otherEquipment = Equipment::create([
            'user_id' => $this->otherLender->id,
            'category_id' => $this->category->id,
            'name' => 'Panneau d\'Amira 400W',
            'brand' => 'Renogy',
            'daily_rate' => 30.00,
            'deposit_amount' => 200.00,
            'status' => 'pending',
        ]);

        $this->actingAs($this->lender)
            ->get(route('equipment.show', $otherEquipment))
            ->assertForbidden();
    }

    public function test_lender_cannot_edit_or_update_another_users_equipment(): void
    {
        $otherEquipment = Equipment::create([
            'user_id' => $this->otherLender->id,
            'category_id' => $this->category->id,
            'name' => 'Panneau d\'Amira 400W',
            'brand' => 'Renogy',
            'daily_rate' => 30.00,
            'deposit_amount' => 200.00,
            'status' => 'pending',
        ]);

        $this->actingAs($this->lender)
            ->get(route('equipment.edit', $otherEquipment))
            ->assertForbidden();

        $this->actingAs($this->lender)
            ->put(route('equipment.update', $otherEquipment), [
                'name' => 'Tentative de piratage',
                'category_id' => $this->category->id,
                'brand' => 'Hacked',
                'daily_rate' => 1.00,
                'deposit_amount' => 1.00,
            ])
            ->assertForbidden();
    }

    public function test_lender_cannot_delete_another_users_equipment(): void
    {
        $otherEquipment = Equipment::create([
            'user_id' => $this->otherLender->id,
            'category_id' => $this->category->id,
            'name' => 'Panneau d\'Amira 400W',
            'brand' => 'Renogy',
            'daily_rate' => 30.00,
            'deposit_amount' => 200.00,
            'status' => 'pending',
        ]);

        $this->actingAs($this->lender)
            ->delete(route('equipment.destroy', $otherEquipment))
            ->assertForbidden();

        $this->assertDatabaseHas('equipment', [
            'id' => $otherEquipment->id,
        ]);
    }

    public function test_lender_can_update_their_own_equipment(): void
    {
        $equipment = Equipment::create([
            'user_id' => $this->lender->id,
            'category_id' => $this->category->id,
            'name' => 'Ancien Nom',
            'brand' => 'EcoFlow',
            'daily_rate' => 15.00,
            'deposit_amount' => 100.00,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->lender)
            ->put(route('equipment.update', $equipment), [
                'name' => 'Nouveau Nom 220W',
                'category_id' => $this->category->id,
                'brand' => 'EcoFlow Pro',
                'daily_rate' => 20.00,
                'deposit_amount' => 150.00,
                'is_available' => true,
            ]);

        $response->assertRedirect(route('equipment.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('equipment', [
            'id' => $equipment->id,
            'name' => 'Nouveau Nom 220W',
            'daily_rate' => 20.00,
        ]);
    }

    public function test_lender_can_delete_their_own_equipment(): void
    {
        $equipment = Equipment::create([
            'user_id' => $this->lender->id,
            'category_id' => $this->category->id,
            'name' => 'Équipement à supprimer',
            'brand' => 'Test',
            'daily_rate' => 10.00,
            'deposit_amount' => 50.00,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->lender)
            ->delete(route('equipment.destroy', $equipment));

        $response->assertRedirect(route('equipment.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('equipment', [
            'id' => $equipment->id,
        ]);
    }

    public function test_eloquent_category_equipment_relationship(): void
    {
        $equipment = Equipment::create([
            'user_id' => $this->lender->id,
            'category_id' => $this->category->id,
            'name' => 'Panneau 200W',
            'brand' => 'EcoFlow',
            'daily_rate' => 15.00,
            'deposit_amount' => 100.00,
        ]);

        // Equipment belongsTo Category
        $this->assertInstanceOf(Category::class, $equipment->category);
        $this->assertEquals($this->category->id, $equipment->category->id);

        // Category hasMany Equipment
        $this->assertTrue($this->category->equipment->contains($equipment));
    }

    public function test_lender_layout_renders_default_navbar_and_sidebar_navigation(): void
    {
        $response = $this->actingAs($this->lender)
            ->get(route('lender.dashboard'));

        $response->assertOk();

        // 1. Default landing page navbar is present at the top
        $response->assertSee('How it works');
        $response->assertSee('Impact');
        $response->assertSee('Community');
        $response->assertSee('FAQ');

        // 2. Sidebar with navigation is present
        $response->assertSee('id="sidebar-link-dashboard"', false);
        $response->assertSee('id="sidebar-link-equipment"', false);
        $response->assertSee('id="sidebar-link-categories"', false);
        $response->assertDontSee('id="sidebar-btn-add-equipment"', false);
    }

    public function test_category_creation_validation_rules_and_old_input(): void
    {
        $response = $this->actingAs($this->lender)
            ->from(route('categories.create'))
            ->post(route('categories.store'), [
                'name' => 'A', // too short (< 2)
                'slug' => 'panneaux-solaires-portables', // already exists
                'description' => 'Test description that should be preserved',
            ]);

        $response->assertRedirect(route('categories.create'));
        $response->assertSessionHasErrors(['name', 'slug']);
        $response->assertSessionHasErrors([
            'name' => 'Le nom de la catégorie doit comporter au moins 2 caractères.',
            'slug' => 'Ce slug de catégorie est déjà utilisé.',
        ]);
        $this->assertEquals('Test description that should be preserved', session('_old_input.description'));
    }

    public function test_category_update_validation_rules(): void
    {
        $otherCategory = Category::create([
            'name' => 'Onduleurs',
            'slug' => 'onduleurs',
        ]);

        $response = $this->actingAs($this->lender)
            ->from(route('categories.edit', $this->category))
            ->put(route('categories.update', $this->category), [
                'name' => '', // required
                'slug' => 'onduleurs', // unique taken by otherCategory
            ]);

        $response->assertRedirect(route('categories.edit', $this->category));
        $response->assertSessionHasErrors(['name', 'slug']);
        $response->assertSessionHasErrors([
            'name' => 'Le nom de la catégorie est obligatoire.',
            'slug' => 'Ce slug est déjà attribué à une autre catégorie.',
        ]);

        // But keeping its own slug should succeed
        $responseSuccess = $this->actingAs($this->lender)
            ->put(route('categories.update', $this->category), [
                'name' => 'Panneaux solaires portables Renouvelés',
                'slug' => 'panneaux-solaires-portables', // same slug is ignored
                'description' => 'Nouvelle description',
            ]);

        $responseSuccess->assertRedirect(route('categories.index'))
            ->assertSessionHas('success');
    }

    public function test_equipment_creation_strict_validation_and_old_input(): void
    {
        $response = $this->actingAs($this->lender)
            ->from(route('equipment.create'))
            ->post(route('equipment.store'), [
                'name' => 'ab', // min:3
                'brand' => 'a', // min:2
                'category_id' => 99999, // non-existent
                'daily_rate' => -5, // min:0.01
                'deposit_amount' => -10, // min:0
                'power_watts' => -50, // min:0
                'capacity_wh' => -100, // min:0
                'description' => 'Preserved description test',
            ]);

        $response->assertRedirect(route('equipment.create'));
        $response->assertSessionHasErrors([
            'name',
            'brand',
            'category_id',
            'daily_rate',
            'deposit_amount',
            'power_watts',
            'capacity_wh',
        ]);

        // Input preservation
        $this->assertEquals('Preserved description test', session('_old_input.description'));
        $this->assertEquals('ab', session('_old_input.name'));
    }

    public function test_equipment_update_strict_validation(): void
    {
        $equipment = Equipment::create([
            'user_id' => $this->lender->id,
            'category_id' => $this->category->id,
            'name' => 'Batterie EcoFlow 1024Wh',
            'brand' => 'EcoFlow',
            'daily_rate' => 25.00,
            'deposit_amount' => 150.00,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->lender)
            ->from(route('equipment.edit', $equipment))
            ->put(route('equipment.update', $equipment), [
                'name' => '', // required
                'brand' => '', // required
                'category_id' => 99999, // invalid
                'daily_rate' => 0, // min:0.01
                'deposit_amount' => -20, // min:0
            ]);

        $response->assertRedirect(route('equipment.edit', $equipment));
        $response->assertSessionHasErrors(['name', 'brand', 'category_id', 'daily_rate', 'deposit_amount']);
    }

    public function test_user_with_rent_role_cannot_access_lender_space(): void
    {
        $renter = User::factory()->create([
            'role' => 'user',
            'solar_role' => 'rent',
        ]);

        $this->actingAs($renter)->get(route('lender.dashboard'))->assertForbidden();
        $this->actingAs($renter)->get(route('categories.index'))->assertForbidden();
        $this->actingAs($renter)->get(route('equipment.index'))->assertForbidden();
        $this->actingAs($renter)->get(route('equipment.create'))->assertForbidden();
    }

    public function test_renter_dashboard_redirect_points_to_rentals(): void
    {
        $renter = User::factory()->create([
            'role' => 'user',
            'solar_role' => 'rent',
        ]);

        $this->actingAs($renter)
            ->get(route('dashboard'))
            ->assertRedirect(route('rentals.index'));
    }

    public function test_user_with_both_role_can_access_lender_space(): void
    {
        $bothUser = User::factory()->create([
            'role' => 'user',
            'solar_role' => 'both',
        ]);

        $this->actingAs($bothUser)
            ->get(route('lender.dashboard'))
            ->assertOk();
    }
}

