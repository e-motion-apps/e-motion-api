<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CityWithoutAssignedCountry;
use Illuminate\Http\JsonResponse;

class CityWithoutAssignedCountryController extends Controller
{
    public function destroy(CityWithoutAssignedCountry $city): JsonResponse
    {
        $city->delete();

        return response()->json([
            "message" => __("City removed successfully!"),
        ]);
    }

    public function destroyAll(): JsonResponse
    {
        CityWithoutAssignedCountry::query()->delete();

        return response()->json([
            "message" => __("All cities removed successfully!"),
        ]);
    }
}
