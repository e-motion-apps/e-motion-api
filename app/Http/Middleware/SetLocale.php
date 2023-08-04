<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next): mixed
    {
        $locale = $request->cookie("locale");

        if (!empty($locale) && in_array($locale, config("app.supported_locales"), true)) {
            app()->setLocale($request->cookie("locale"));
        }

        return $next($request);
    }
}
