<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\EnergyKit;
use App\Models\EnergyKitReservation;
use App\Models\Equipment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnergyKitLenderReservationVisibilityTest extends TestCase
{
    use RefreshDatabase;

    protected User $lenderUser;
    protected User $otherLender;
    protected User $renterUser;
    protected Category $category;
    protected Equipment $equipment;
    protected EnergyKit $kit;
    protected EnergyKitReservation $reservation;

    protected function setUp(): void
    {
        parent::setUp();

        $this->lenderUser = User::factory()->create([
            'name' => 'Lender John',
            'email' => 'lender.john@solarshare.tn',
            'role' => 'user',
            'solar_role' => 'lend',
        ]);

        $this->otherLender = User::factory()->create([
            'name' => 'Other Lender',
            'email' => 'other.lender@solarshare.tn',
            'role' => 'user',
            'solar_role' => 'lend',
        ]);

        $this->renterUser = User::factory()->create([
            'name' => 'Renter Alice',
            'email' => 'renter.alice@solarshare.tn',
            'role' => 'user',
            'solar_role' => 'rent',
        ]);

        $this->category = Category::factory()->create(['name' => 'Onduleurs']);

        $this->equipment = Equipment::factory()->create([
            'user_id' => $this->lenderUser->id,
            'category_id' => $this->category->id,
            'name' => 'Onduleur Pur Sinus 1000W',
            'status' => 'approved',
            'is_available' => true,
            'daily_rate' => 32.27,
            'deposit_amount' => 284.79,
        ]);

        $this->kit = EnergyKit::create([
            'user_id' => $this->lenderUser->id,
            'title' => 'Pack Solaire Autonome 1000W',
            'description' => 'Pack complet pour camping et sites isolés.',
            'target_use' => 'camping',
            'discount_percentage' => 15.00,
            'is_active' => true,
        ]);

        $this->kit->equipment()->attach($this->equipment->id, [
            'quantity' => 1,
            'options' => '500W | 512Wh',
        ]);

        $this->reservation = EnergyKitReservation::create([
            'energy_kit_id' => $this->kit->id,
            'user_id' => $this->renterUser->id,
            'start_date' => Carbon::today()->addDays(2),
            'end_date' => Carbon::today()->addDays(5),
            'days_count' => 4,
            'daily_rate' => 27.43,
            'total_price' => 109.72,
            'deposit_amount' => 284.79,
            'status' => 'pending',
            'notes' => 'Besoin pour un camping le week-end.',
        ]);
    }

    public function test_lender_can_view_pack_reservations_in_lender_rentals_index(): void
    {
        $response = $this->actingAs($this->lenderUser)
            ->get(route('lender.rentals.index', ['tab' => 'packs']));

        $response->assertStatus(200);
        $response->assertSee('Pack Solaire Autonome 1000W');
        $response->assertSee('Renter Alice');
        $response->assertSee('109.72');
        $response->assertSee('27.43');
        $response->assertSee('Remise -15%');
        $response->assertSee('En attente');
        $response->assertSee('Confirmer');
    }

    public function test_lender_kpis_include_pack_reservations(): void
    {
        $response = $this->actingAs($this->lenderUser)
            ->get(route('lender.rentals.index'));

        $response->assertStatus(200);
        // Total should count the pack reservation
        $response->assertSee('Total Réservations');
        $response->assertSee('1 pack(s)');
        $response->assertSee('Packs Solaires Clé-en-main');
    }

    public function test_lender_can_see_pack_reservations_on_pack_detail_page(): void
    {
        $response = $this->actingAs($this->lenderUser)
            ->get(route('energy-kits.show', $this->kit->slug));

        $response->assertStatus(200);
        $response->assertSee('Réservations Reçues sur ce Pack Solaire');
        $response->assertSee('Renter Alice');
        $response->assertSee('109.72 TND');
        $response->assertSee('Besoin pour un camping le week-end.');
    }

    public function test_lender_can_update_pack_reservation_status_to_confirmed(): void
    {
        $response = $this->actingAs($this->lenderUser)
            ->patch(route('lender.kit-reservations.status', $this->reservation), [
                'status' => 'confirmed',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('confirmed', $this->reservation->fresh()->status);
    }

    public function test_other_lender_cannot_update_pack_reservation_status(): void
    {
        $response = $this->actingAs($this->otherLender)
            ->patch(route('lender.kit-reservations.status', $this->reservation), [
                'status' => 'confirmed',
            ]);

        $response->assertStatus(403);
        $this->assertEquals('pending', $this->reservation->fresh()->status);
    }

    public function test_renter_can_view_their_pack_reservations_in_rentals_index(): void
    {
        $response = $this->actingAs($this->renterUser)
            ->get(route('rentals.index'));

        $response->assertStatus(200);
        $response->assertSee('Mes Packs Solaires Réservés');
        $response->assertSee('Pack Solaire Autonome 1000W');
        $response->assertSee('109.72 TND');
    }
}
