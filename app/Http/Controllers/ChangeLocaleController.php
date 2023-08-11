<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ChangeLocaleController extends Controller
{
    public function __invoke(Request $request, string $locale): RedirectResponse
    {
        if (in_array($locale, config("app.supported_locales"), true)) {
            app()->setLocale($locale);

            return redirect()->back()
                ->with("success", __("Language has been changed."))
                ->withCookie(cookie("locale", $locale));
        }

        return redirect()->back()
            ->with("error", __("Error changing the language."));
    }
}
