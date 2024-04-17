<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\ProviderResource;
use App\Models\Favorites;
use App\Models\Provider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $favoriteCities = $user->favorites()->with(["city.country", "city.cityProviders"])->get();

        $cities = $favoriteCities->map(fn($favorite) => CityResource::make($favorite->city));

        $providers = Provider::all();

        return response()->json([
            "cities" => $cities,
            "providers" => ProviderResource::collection($providers),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $cityId = $request->input("city_id");
        $userId = $request->user()?->id;

        $favorite = Favorites::firstOrCreate([
            "user_id" => $userId,
            "city_id" => $cityId,
        ]);

        if ($favorite->wasRecentlyCreated) {
            return response()->json([
                "message" => "City added to favorites.",
            ]);
        }
        $favorite->delete();

        return response()->json([
            "message" => "City removed from favorites.",
        ]);
    }

    public function check(Request $request, int $cityId): bool
    {
        $userId = $request->user()->id;

        return Favorites::where("user_id", $userId)
            ->where("city_id", $cityId)
            ->exists();
    }
}
