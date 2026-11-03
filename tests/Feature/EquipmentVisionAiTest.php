<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Services\GeminiEquipmentVisionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class EquipmentVisionAiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Category::create([
            'name' => 'Panneaux solaires portables',
            'slug' => 'panneaux-solaires-portables',
            'description' => 'Panneaux pliables',
        ]);
    }

    public function test_guest_cannot_access_ai_photo_analysis(): void
    {
        $response = $this->postJson(route('equipment.analyze-photo'), []);

        $response->assertStatus(401);
    }

    public function test_renter_role_is_forbidden_from_lender_photo_analysis(): void
    {
        $renter = User::factory()->create([
            'role' => 'user',
            'solar_role' => 'borrow',
        ]);

        $response = $this->actingAs($renter)->postJson(route('equipment.analyze-photo'), []);

        $this->assertTrue(in_array($response->status(), [302, 403]));
    }

    public function test_lender_validation_fails_without_image(): void
    {
        $lender = User::factory()->create([
            'role' => 'user',
            'solar_role' => 'lend',
        ]);

        $response = $this->actingAs($lender)->postJson(route('equipment.analyze-photo'), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['image']);
    }

    public function test_lender_successfully_analyzes_valid_equipment_photo(): void
    {
        $lender = User::factory()->create([
            'role' => 'user',
            'solar_role' => 'lend',
        ]);

        $fakeService = $this->mock(GeminiEquipmentVisionService::class, function ($mock) {
            $mock->shouldReceive('analyzePhoto')
                ->once()
                ->andReturn([
                    'success' => true,
                    'data' => [
                        'is_valid_equipment' => true,
                        'is_blurry_or_low_quality' => false,
                        'quality_warning' => null,
                        'identified_type' => 'Panneau solaire pliable 200W',
                        'name' => 'Panneau Solaire Pliable 200W EcoFlow',
                        'brand' => 'EcoFlow',
                        'matched_category_id' => 1,
                        'power_watts' => 200,
                        'capacity_wh' => null,
                        'daily_rate' => 18.00,
                        'deposit_amount' => 120.00,
                        'damage_detected' => false,
                        'damage_assessment' => 'Aucun dommage détecté.',
                        'condition_summary' => 'Très bon état',
                        'deposit_recommendation_reason' => 'Dépôt standard',
                        'description' => 'Panneau solaire de qualité supérieure.',
                    ],
                ]);
        });

        $file = UploadedFile::fake()->image('solar_panel.jpg', 800, 600);

        $response = $this->actingAs($lender)->postJson(route('equipment.analyze-photo'), [
            'image' => $file,
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'data' => [
                'name' => 'Panneau Solaire Pliable 200W EcoFlow',
                'brand' => 'EcoFlow',
                'damage_detected' => false,
            ],
        ]);
    }
}
