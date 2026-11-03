<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Supported application locales.
     *
     * @var array<string>
     */
    public const SUPPORTED_LOCALES = ['fr', 'en', 'ar'];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = null;

        // 1. Check session
        if ($request->hasSession() && $request->session()->has('locale')) {
            $candidate = $request->session()->get('locale');
            if (in_array($candidate, self::SUPPORTED_LOCALES, true)) {
                $locale = $candidate;
            }
        }

        // 2. Check cookie if session was empty
        if (! $locale && $request->hasCookie('locale')) {
            $candidate = $request->cookie('locale');
            if (in_array($candidate, self::SUPPORTED_LOCALES, true)) {
                $locale = $candidate;
                if ($request->hasSession()) {
                    $request->session()->put('locale', $locale);
                }
            }
        }

        // 3. Fallback to browser preferred language or config
        if (! $locale) {
            if (app()->environment('testing')) {
                $locale = config('app.locale', 'fr');
            } else {
                $preferred = $request->getPreferredLanguage(self::SUPPORTED_LOCALES);
                $locale = $preferred ?: config('app.locale', 'fr');
            }
            if (! in_array($locale, self::SUPPORTED_LOCALES, true)) {
                $locale = 'fr';
            }
        }

        App::setLocale($locale);
        \Carbon\Carbon::setLocale($locale);

        $response = $next($request);

        // Ensure persistent cookie matches active locale
        if ($request->cookie('locale') !== $locale) {
            $response->headers->setCookie(cookie()->forever('locale', $locale));
        }

        return $response;
    }
}
