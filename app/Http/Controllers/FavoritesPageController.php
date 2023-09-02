<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\CityResource;
use App\Http\Resources\ProviderResource;
use App\Models\Provider;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class FavoritesPageController extends Controller
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
}
