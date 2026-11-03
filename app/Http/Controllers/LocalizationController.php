<?php

namespace App\Http\Controllers;

use App\Http\Middleware\SetLocale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

class LocalizationController extends Controller
{
    /**
     * Switch application locale and redirect back.
     */
    public function switch(Request $request, string $locale): RedirectResponse
    {
        if (in_array($locale, SetLocale::SUPPORTED_LOCALES, true)) {
            session(['locale' => $locale]);
            if ($request->hasSession()) {
                $request->session()->put('locale', $locale);
                $request->session()->save();
            }
            App::setLocale($locale);
            Cookie::queue(Cookie::forever('locale', $locale));
        }

        $fallback = route('home');
        $previous = url()->previous();

        if (! $previous || str_contains($previous, '/lang/')) {
            return redirect($fallback)->withCookie(cookie()->forever('locale', $locale));
        }

        return redirect()->to($previous)->withCookie(cookie()->forever('locale', $locale));
    }
}
