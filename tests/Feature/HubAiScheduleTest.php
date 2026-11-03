<?php

namespace Tests\Feature;

use App\Models\CollectionHub;
use App\Models\OperatingSlot;
use App\Models\User;
use App\Services\GeminiHubScheduleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class HubAiScheduleTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected CollectionHub $hub;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'solar_role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->hub = CollectionHub::create([
            'name' => 'Hub Relais Test AI',
            'address' => '12 Rue de l\'Énergie',
            'city' => 'Tunis',
            'postal_code' => '1000',
            'contact_phone' => '+216 71 000 000',
            'contact_email' => 'hub.test@solarshare.tn',
            'max_capacity' => 20,
            'is_active' => true,
        ]);
    }

    public function test_guest_cannot_access_ai_slots_generation(): void
    {
        $response = $this->postJson(route('admin.hubs.ai-generate-slots', $this->hub), [
            'description' => 'Ouvert du lundi au vendredi de 9h à 18h',
        ]);

        $response->assertUnauthorized();
    }

    public function test_non_admin_cannot_access_ai_slots_generation(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
            'solar_role' => 'rent',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->postJson(route('admin.hubs.ai-generate-slots', $this->hub), [
            'description' => 'Ouvert du lundi au vendredi de 9h à 18h',
        ]);

        $response->assertForbidden();
    }

    public function test_admin_can_generate_slots_with_ai_assistant(): void
    {
        $mockService = Mockery::mock(GeminiHubScheduleService::class);
        $mockService->shouldReceive('generateSlots')
            ->once()
            ->with('Ouvert du lundi au vendredi 9h à 18h avec pause déjeuner')
            ->andReturn([
                [
                    'day_of_week' => 'lundi',
                    'open_time' => '09:00',
                    'close_time' => '12:30',
                    'notes' => 'Matinée',
                ],
                [
                    'day_of_week' => 'lundi',
                    'open_time' => '14:00',
                    'close_time' => '18:00',
                    'notes' => 'Après-midi',
                ],
            ]);

        $this->app->instance(GeminiHubScheduleService::class, $mockService);

        $response = $this->actingAs($this->admin)->postJson(route('admin.hubs.ai-generate-slots', $this->hub), [
            'description' => 'Ouvert du lundi au vendredi 9h à 18h avec pause déjeuner',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'total_proposed' => 2,
                'valid_count' => 2,
                'has_warnings' => false,
            ]);

        // Verify that nothing was persisted to database yet
        $this->assertEquals(0, OperatingSlot::count());
    }

    public function test_ai_generation_detects_overlap_with_existing_slots(): void
    {
        // Existing slot on Lundi 09:00 - 18:00
        $this->hub->operatingSlots()->create([
            'day_of_week' => 'lundi',
            'open_time' => '09:00',
            'close_time' => '18:00',
            'notes' => 'Créneau existant',
            'is_active' => true,
        ]);

        $mockService = Mockery::mock(GeminiHubScheduleService::class);
        $mockService->shouldReceive('generateSlots')
            ->once()
            ->andReturn([
                [
                    'day_of_week' => 'lundi',
                    'open_time' => '10:00',
                    'close_time' => '12:00',
                    'notes' => 'Créneau en chevauchement',
                ],
                [
                    'day_of_week' => 'mardi',
                    'open_time' => '09:00',
                    'close_time' => '17:00',
                    'notes' => 'Créneau sans chevauchement',
                ],
            ]);

        $this->app->instance(GeminiHubScheduleService::class, $mockService);

        $response = $this->actingAs($this->admin)->postJson(route('admin.hubs.ai-generate-slots', $this->hub), [
            'description' => 'Horaires de permanence',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'total_proposed' => 2,
                'valid_count' => 1,
                'has_warnings' => true,
            ]);

        $slots = $response->json('slots');
        $this->assertFalse($slots[0]['is_valid']);
        $this->assertStringContainsString('Chevauchement détecté', $slots[0]['warning']);
        $this->assertTrue($slots[1]['is_valid']);
    }

    public function test_admin_can_confirm_and_apply_ai_generated_slots(): void
    {
        $payload = [
            'slots' => [
                [
                    'day_of_week' => 'lundi',
                    'open_time' => '09:00',
                    'close_time' => '12:30',
                    'notes' => 'Matinée Lundi',
                ],
                [
                    'day_of_week' => 'lundi',
                    'open_time' => '14:00',
                    'close_time' => '18:00',
                    'notes' => 'Après-midi Lundi',
                ],
                [
                    'day_of_week' => 'samedi',
                    'open_time' => '09:00',
                    'close_time' => '13:00',
                    'notes' => 'Matinée Samedi',
                ],
            ],
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.hubs.ai-apply-slots', $this->hub), $payload);

        $response->assertRedirect(route('admin.hubs.show', $this->hub))
            ->assertSessionHas('success');

        $this->assertEquals(3, $this->hub->operatingSlots()->count());

        $this->assertDatabaseHas('operating_slots', [
            'collection_hub_id' => $this->hub->id,
            'day_of_week' => 'lundi',
            'open_time' => '09:00',
            'close_time' => '12:30',
        ]);

        $this->assertDatabaseHas('operating_slots', [
            'collection_hub_id' => $this->hub->id,
            'day_of_week' => 'samedi',
            'open_time' => '09:00',
            'close_time' => '13:00',
        ]);
    }
}

