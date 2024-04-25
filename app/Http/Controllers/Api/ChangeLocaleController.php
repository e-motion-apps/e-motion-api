<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChangeLocaleController extends Controller
{
    public function __invoke(Request $request, string $locale): JsonResponse
    {
        if (in_array($locale, config("app.supported_locales"), true)) {
            app()->setLocale($locale);

            return response()->json([
                "message" => __("Language has been changed."),
            ]);
        }

        return response()->json([
            "message" => __("Error changing the language."),
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
