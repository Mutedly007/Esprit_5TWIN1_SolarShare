<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Recaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            $fail("Veuillez cocher la case 'Je ne suis pas un robot'.");
            return;
        }

        // Allow simulated valid tokens in testing environment
        if (app()->environment('testing')) {
            if ($value === 'invalid-test-token') {
                $fail("La vérification du reCAPTCHA a échoué. Veuillez réessayer.");
                return;
            }
            if ($value === 'valid-test-token') {
                return;
            }
        }

        $secret = config('services.recaptcha.secret_key');
        if (empty($secret)) {
            return;
        }

        try {
            $options = [];
            $caBundle = storage_path('certs/cacert.pem');
            if (file_exists($caBundle)) {
                $options['verify'] = $caBundle;
            }

            $response = Http::withOptions($options)
                ->asForm()
                ->timeout(8)
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret'   => $secret,
                    'response' => $value,
                    'remoteip' => request()->ip(),
                ]);

            if (! $response->successful() || ! ($response->json('success') === true)) {
                Log::warning('Google reCAPTCHA verification failed', [
                    'ip'          => request()->ip(),
                    'error_codes' => $response->json('error-codes'),
                ]);
                $fail("La vérification du reCAPTCHA a échoué. Veuillez réessayer.");
            }
        } catch (\Throwable $e) {
            Log::error('Google reCAPTCHA connection error: ' . $e->getMessage());
            $fail("Impossible de vérifier le reCAPTCHA pour le moment. Veuillez réessayer.");
        }
    }
}

