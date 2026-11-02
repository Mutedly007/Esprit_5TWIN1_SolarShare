<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered(): void
    {
        $user = User::factory()->unverified()->create([
            'solar_role' => 'rent',
        ]);

        $response = $this->actingAs($user)->get(route('verification.notice'));

        $response->assertStatus(200);
        $response->assertSee('Verify your email address');
    }

    public function test_registration_redirects_to_verification_notice_and_dispatches_event(): void
    {
        Event::fake([Registered::class]);

        $response = $this->post(route('register.store'), [
            'name'                  => 'New Solar Member',
            'email'                 => 'newmember@example.com',
            'solar_role'            => 'rent',
            'password'              => 'SolarPower123!',
            'password_confirmation' => 'SolarPower123!',
        ]);

        $response->assertRedirect(route('verification.notice'));
        Event::assertDispatched(Registered::class);

        $user = User::where('email', 'newmember@example.com')->first();
        $this->assertNotNull($user);
        $this->assertNull($user->email_verified_at);
        $this->assertFalse($user->hasVerifiedEmail());
    }

    public function test_unverified_user_cannot_access_account_and_is_redirected_to_verify(): void
    {
        $user = User::factory()->unverified()->create([
            'solar_role' => 'rent',
        ]);

        // Trying to access dashboard / onboarding / profile while unverified must redirect to verification.notice
        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertRedirect(route('verification.notice'));
    }

    public function test_unverified_user_login_redirects_to_verification_notice(): void
    {
        $user = User::factory()->unverified()->create([
            'email'      => 'unverified@example.com',
            'password'   => Hash::make('SolarPower123!'),
            'solar_role' => 'rent',
        ]);

        $response = $this->post(route('login.attempt'), [
            'email'    => 'unverified@example.com',
            'password' => 'SolarPower123!',
        ]);

        $response->assertRedirect(route('verification.notice'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_email_can_be_verified(): void
    {
        Event::fake([Verified::class]);

        $user = User::factory()->unverified()->create([
            'solar_role' => 'rent',
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        $user->refresh();
        $this->assertTrue($user->hasVerifiedEmail());
        Event::assertDispatched(Verified::class);
        $response->assertRedirect(route('home'));
    }

    public function test_email_is_not_verified_with_invalid_hash(): void
    {
        $user = User::factory()->unverified()->create([
            'solar_role' => 'rent',
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email@example.com')]
        );

        $this->actingAs($user)->get($verificationUrl);

        $user->refresh();
        $this->assertFalse($user->hasVerifiedEmail());
    }
}
