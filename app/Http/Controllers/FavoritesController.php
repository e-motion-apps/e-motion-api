<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\CityResource;
use App\Http\Resources\ProviderResource;
use App\Models\Favorites;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Session\Store as Session;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class FavoritesController extends Controller
{
    public function index(): Response
    {
        $user = Auth::user();

        $favoriteCities = $user->favorites()->with(["city.country", "city.cityProviders"])->get();

        $cities = $favoriteCities->map(fn($favorite) => CityResource::make($favorite->city));

        $providers = Provider::all();

        return Inertia::render("FavoriteCities/Index", [
            "cities" => $cities,
            "providers" => ProviderResource::collection($providers),
        ]);
    }

    public function store(Request $request, Session $session): void
    {
        $cityId = $request->input("city_id");
        $userId = $request->user()?->id;

        $favorite = Favorites::firstOrCreate(
            ["user_id" => $userId, "city_id" => $cityId],
        );

        if ($favorite->wasRecentlyCreated) {
            $session->flash("message", "City added to favorites.");
        } else {
            $favorite->delete();
            $session->flash("message", "City removed from favorites.");
        }
    }

    public function check(Request $request, int $cityId): bool
    {
        $userId = $request->user()?->id;

        return Favorites::where("user_id", $userId)
            ->where("city_id", $cityId)
            ->exists();
    }
}
