<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ChangeLocaleController extends Controller
{
    public function __invoke(Request $request, string $locale): RedirectResponse
    {
        if (in_array($locale, config("app.supported_locales"))) {
            app()->setLocale($locale);

            return redirect()->back()
                ->with("success", __("Language has been changed."))
                ->withCookie(cookie("locale", $locale));
        }

        return redirect()->back()
            ->with("error", __("Error changing the language."));
    }
}
