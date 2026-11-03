<?php

namespace Tests\Feature;

use App\Services\GeminiEnergyKitChatService;
use Tests\TestCase;

class EnergyKitChatbotTest extends TestCase
{
    public function test_energy_kit_chatbot_requires_message(): void
    {
        $response = $this->postJson(route('energy-kits.chat'), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['message']);
    }

    public function test_energy_kit_chatbot_returns_successful_reply(): void
    {
        $mock = $this->mock(GeminiEnergyKitChatService::class);
        $mock->shouldReceive('reply')
            ->once()
            ->with(
                \Mockery::on(fn ($messages) => count($messages) === 1 && $messages[0]['text'] === 'Quel pack choisir pour du camping ?'),
                \Mockery::any(),
                \Mockery::type('string')
            )
            ->andReturn('Pour le camping, privilégiez un pack solaire combinant panneau pliable 200W et batterie LiFePO4.');

        $response = $this->postJson(route('energy-kits.chat'), [
            'message' => 'Quel pack choisir pour du camping ?',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'reply' => 'Pour le camping, privilégiez un pack solaire combinant panneau pliable 200W et batterie LiFePO4.',
        ]);
    }

    public function test_energy_kit_chatbot_detects_specific_pack_navigation(): void
    {
        $mock = $this->mock(GeminiEnergyKitChatService::class);
        $mock->shouldReceive('reply')
            ->once()
            ->andReturn('Je vous recommande le [Pack Vanlife Autonomie](/energy-kits/pack-vanlife-autonomie) avec -15% de remise.');

        $response = $this->postJson(route('energy-kits.chat'), [
            'message' => 'Conseille-moi un pack vanlife',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('status', 'success');
        $response->assertJsonPath('navigation.url', url('/energy-kits/pack-vanlife-autonomie'));
    }

    public function test_system_instruction_enforces_strict_solar_packs_guardrail(): void
    {
        $service = new GeminiEnergyKitChatService();
        $instruction = $service->buildSystemInstruction(null, 'fr');

        $this->assertStringContainsString('PackBot', $instruction);
        $this->assertStringContainsString('EXCLUSIVEMENT ET STRICTEMENT', $instruction);
        $this->assertStringContainsString('Packs Solaires Clé-en-main', $instruction);
    }
}

