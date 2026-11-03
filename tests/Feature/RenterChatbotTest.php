<?php

namespace Tests\Feature;

use App\Services\GeminiRenterChatService;
use Tests\TestCase;

class RenterChatbotTest extends TestCase
{
    public function test_renter_chatbot_requires_message(): void
    {
        $response = $this->postJson(route('renter.chat'), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['message']);
    }

    public function test_renter_chatbot_returns_successful_reply(): void
    {
        // Mock the Gemini service to avoid consuming quota in unit tests
        $mock = $this->mock(GeminiRenterChatService::class);
        $mock->shouldReceive('reply')
            ->once()
            ->with(\Mockery::on(function ($messages) {
                return count($messages) === 1 && $messages[0]['text'] === 'Comment fonctionne l état des lieux ?';
            }))
            ->andReturn('L état des lieux se fait en deux étapes : check-in et check-out.');

        $response = $this->postJson(route('renter.chat'), [
            'message' => 'Comment fonctionne l état des lieux ?',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'reply'  => 'L état des lieux se fait en deux étapes : check-in et check-out.',
        ]);
    }

    public function test_renter_chatbot_handles_history_context(): void
    {
        $mock = $this->mock(GeminiRenterChatService::class);
        $mock->shouldReceive('reply')
            ->once()
            ->with(\Mockery::on(function ($messages) {
                return count($messages) === 3;
            }))
            ->andReturn('Vous pouvez choisir un hub sur la carte ou la liste des points relais.');

        $response = $this->postJson(route('renter.chat'), [
            'message' => 'Et pour le retrait ?',
            'history' => [
                ['role' => 'user', 'text' => 'Comment réserver un pack solaire ?'],
                ['role' => 'model', 'text' => 'Vous pouvez choisir un pack solaire dans la section dédiée.'],
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'reply'  => 'Vous pouvez choisir un hub sur la carte ou la liste des points relais.',
        ]);
    }

    public function test_renter_chatbot_detects_navigation_target(): void
    {
        $mock = $this->mock(GeminiRenterChatService::class);
        $mock->shouldReceive('reply')
            ->once()
            ->andReturn('Voici le lien pour accéder directement à la marketplace : [Accéder à la Marketplace](/marketplace)');

        $response = $this->postJson(route('renter.chat'), [
            'message' => 'Emmène-moi à la marketplace',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status'     => 'success',
            'navigation' => [
                'url'   => '/marketplace',
                'label' => 'Marketplace des Équipements',
            ],
        ]);
    }
}

