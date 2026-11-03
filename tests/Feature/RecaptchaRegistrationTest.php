<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RecaptchaRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_requires_recaptcha_response_when_enforced(): void
    {
        $response = $this->post(route('register.store'), [
            'name'                  => 'Recaptcha Tester',
            'email'                 => 'recaptcha-test@example.com',
            'solar_role'            => 'rent',
            'password'              => 'SunnyDay1!',
            'password_confirmation' => 'SunnyDay1!',
            'g-recaptcha-response'  => '',
        ]);

        $response->assertSessionHasErrors('g-recaptcha-response');
        $this->assertDatabaseMissing(User::class, ['email' => 'recaptcha-test@example.com']);
    }

    public function test_registration_fails_when_recaptcha_is_invalid(): void
    {
        $response = $this->post(route('register.store'), [
            'name'                  => 'Invalid Recaptcha Tester',
            'email'                 => 'invalid-captcha@example.com',
            'solar_role'            => 'rent',
            'password'              => 'SunnyDay1!',
            'password_confirmation' => 'SunnyDay1!',
            'g-recaptcha-response'  => 'invalid-test-token',
        ]);

        $response->assertSessionHasErrors('g-recaptcha-response');
        $this->assertDatabaseMissing(User::class, ['email' => 'invalid-captcha@example.com']);
    }

    public function test_registration_succeeds_with_valid_recaptcha_token(): void
    {
        $response = $this->post(route('register.store'), [
            'name'                  => 'Valid Recaptcha Tester',
            'email'                 => 'valid-captcha@example.com',
            'solar_role'            => 'both',
            'password'              => 'SunnyDay1!',
            'password_confirmation' => 'SunnyDay1!',
            'g-recaptcha-response'  => 'valid-test-token',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas(User::class, [
            'email'      => 'valid-captcha@example.com',
            'solar_role' => 'both',
        ]);
    }

    public function test_recaptcha_rule_contacts_google_api_and_verifies_success(): void
    {
        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'challenge_ts' => now()->toIso8601String(),
                'hostname' => '127.0.0.1',
            ], 200),
        ]);

        $response = $this->post(route('register.store'), [
            'name'                  => 'Mocked Recaptcha User',
            'email'                 => 'mocked-recaptcha@example.com',
            'solar_role'            => 'rent',
            'password'              => 'SunnyDay1!',
            'password_confirmation' => 'SunnyDay1!',
            'g-recaptcha-response'  => 'real-simulated-token-12345',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas(User::class, ['email' => 'mocked-recaptcha@example.com']);
    }

    public function test_recaptcha_rule_fails_when_google_returns_false(): void
    {
        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => false,
                'error-codes' => ['invalid-input-response'],
            ], 200),
        ]);

        $response = $this->post(route('register.store'), [
            'name'                  => 'Failed Mock User',
            'email'                 => 'failed-mock@example.com',
            'solar_role'            => 'rent',
            'password'              => 'SunnyDay1!',
            'password_confirmation' => 'SunnyDay1!',
            'g-recaptcha-response'  => 'fake-invalid-token-999',
        ]);

        $response->assertSessionHasErrors('g-recaptcha-response');
        $this->assertDatabaseMissing(User::class, ['email' => 'failed-mock@example.com']);
    }
}
