<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketplaceTest extends TestCase
{
    use RefreshDatabase;

    protected User $lender;
    protected Category $category;
    protected Equipment $equipment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->lender = User::factory()->create([
            'name' => 'Sqnty x',
            'email' => 'sqnty@solarshare.tn',
            'role' => 'user',
            'solar_role' => 'lend',
        ]);

        $this->category = Category::create([
            'name' => 'Panneaux solaires portables',
            'slug' => 'panneaux-solaires-portables',
            'description' => 'Panneaux pliables et valises solaires',
        ]);

        $this->equipment = Equipment::create([
            'user_id' => $this->lender->id,
            'category_id' => $this->category->id,
            'name' => 'test solaire 200W',
            'brand' => 'Bluetti',
            'description' => 'Panneau solaire de test haute performance',
            'power_watts' => 200,
            'capacity_wh' => 487,
            'daily_rate' => 50.00,
            'deposit_amount' => 100.00,
            'is_available' => true,
            'status' => 'pending',
        ]);
    }

    public function test_guests_can_access_marketplace(): void
    {
        $response = $this->get(route('marketplace'));

        $response->assertOk()
            ->assertSee('Marketplace')
            ->assertSee('Panneaux solaires portables')
            ->assertSee('test solaire 200W')
            ->assertSee('Bluetti')
            ->assertSee('50.00');
    }

    public function test_navbar_equipment_link_points_to_marketplace(): void
    {
        $response = $this->get(url('/'));

        $response->assertOk()
            ->assertSee(route('marketplace'));
    }

    public function test_marketplace_category_filter(): void
    {
        $otherCat = Category::create([
            'name' => 'Micro-éoliennes',
            'slug' => 'micro-eoliennes',
            'description' => 'Éoliennes compactes',
        ]);

        $response = $this->get(route('marketplace', ['category' => 'panneaux-solaires-portables']));

        $response->assertOk()
            ->assertSee('test solaire 200W');

        $emptyCatResponse = $this->get(route('marketplace', ['category' => 'micro-eoliennes']));
        $emptyCatResponse->assertOk()
            ->assertDontSee('test solaire 200W')
            ->assertSee('Aucun équipement ne correspond à votre recherche');
    }

    public function test_marketplace_search_filter(): void
    {
        $response = $this->get(route('marketplace', ['search' => 'Bluetti']));
        $response->assertOk()
            ->assertSee('test solaire 200W');

        $noMatchResponse = $this->get(route('marketplace', ['search' => 'NonExistentBrandXYZ']));
        $noMatchResponse->assertOk()
            ->assertDontSee('test solaire 200W')
            ->assertSee('Aucun équipement ne correspond à votre recherche');
    }

    public function test_guest_can_view_equipment_details_page(): void
    {
        $response = $this->get(route('marketplace.show', $this->equipment));

        $response->assertOk()
            ->assertSee('test solaire 200W')
            ->assertSee('Bluetti')
            ->assertSee('Panneaux solaires portables')
            ->assertSee('Sqnty x')
            ->assertSee('50.00');
    }
}
