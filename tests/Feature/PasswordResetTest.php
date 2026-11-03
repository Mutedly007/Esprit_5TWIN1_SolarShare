<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_screen_can_be_rendered(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
        $response->assertSee('Reset your password');
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
        ]);

        $response = $this->post(route('password.email'), [
            'email' => $user->email,
        ]);

        $response->assertSessionHas('status');
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $user->email,
        ]);
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
        ]);

        $token = Password::createToken($user);

        $response = $this->get(route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Create new password');
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        Event::fake([PasswordReset::class]);

        $user = User::factory()->create([
            'email'    => 'testuser@example.com',
            'password' => Hash::make('OldPassword123!'),
        ]);

        $token = Password::createToken($user);

        $response = $this->post(route('password.update'), [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => 'NewSecurePass1!',
            'password_confirmation' => 'NewSecurePass1!',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('login'));

        $user->refresh();
        $this->assertTrue(Hash::check('NewSecurePass1!', $user->password));

        // The token should be consumed/removed from password_reset_tokens
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => $user->email,
        ]);

        Event::assertDispatched(PasswordReset::class);
    }

    public function test_password_cannot_be_reset_with_invalid_token(): void
    {
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
        ]);

        $response = $this->post(route('password.update'), [
            'token'                 => 'invalid-token-12345',
            'email'                 => $user->email,
            'password'              => 'NewSecurePass1!',
            'password_confirmation' => 'NewSecurePass1!',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_password_reset_token_expires_after_configured_timeout(): void
    {
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
        ]);

        $token = Password::createToken($user);

        // Fast forward time past the 60-minute expiration window (e.g. 65 minutes)
        Carbon::setTestNow(now()->addMinutes(65));

        $response = $this->post(route('password.update'), [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => 'NewSecurePass1!',
            'password_confirmation' => 'NewSecurePass1!',
        ]);

        $response->assertSessionHasErrors('email');

        Carbon::setTestNow(); // Reset time
    }
}

