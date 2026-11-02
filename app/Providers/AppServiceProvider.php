<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrapFive();

        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $resetUrl = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject('☀️ Réinitialisation de votre mot de passe — SolarShare')
                ->greeting('Bonjour ' . ($notifiable->name ?? 'Cher membre') . ',')
                ->line('Vous recevez cet email car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte SolarShare.')
                ->action('Réinitialiser mon mot de passe', $resetUrl)
                ->line('⏱️ Ce lien de réinitialisation expirera dans ' . config('auth.passwords.users.expire', 60) . ' minutes.')
                ->line('Si vous n\'avez pas demandé cette réinitialisation, vous pouvez ignorer cet email en toute sécurité.')
                ->salutation("L'équipe SolarShare ☀️");
        });

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('☀️ Vérification de votre adresse email — SolarShare')
                ->greeting('Bonjour ' . ($notifiable->name ?? 'Cher membre') . ',')
                ->line('Merci d\'avoir rejoint la communauté SolarShare ! Veuillez cliquer sur le bouton ci-dessous pour confirmer votre adresse email.')
                ->action('Vérifier mon adresse email', $url)
                ->line('Si vous n\'avez pas créé de compte SolarShare, aucune action n\'est nécessaire.')
                ->salutation("L'équipe SolarShare ☀️");
        });
    }
}
