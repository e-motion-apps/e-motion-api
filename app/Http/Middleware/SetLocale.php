<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Middleware\HandleInertiaRequests;

class SetLocale
{
    public function handle(Request $request, Closure $next): mixed
    {
        $locale = $request->cookie("locale");

        if (!empty($locale) && in_array($locale, config("app.supported_locales"))) {
            app()->setLocale($request->cookie("locale"));
        }

        return $next($request);
    }
}