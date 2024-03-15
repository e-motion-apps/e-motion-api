<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProviderResource;
use App\Models\City;
use App\Models\CityProvider;
use App\Models\Country;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $usersCount = User::count();

        $citiesWithProviders = CityProvider::distinct("city_id")->get();
        $citiesWithProvidersCount = $citiesWithProviders->count();

        $citiesWithProvidersIds = $citiesWithProviders->pluck("city_id");
        $countriesWithCitiesWithProvidersIds = City::whereIn("id", $citiesWithProvidersIds)->distinct()->pluck("country_id");
        $countriesWithCitiesWithProvidersCount = Country::whereIn("id", $countriesWithCitiesWithProvidersIds)->count();

        $providersCount = Provider::count();

        $providerCitiesCount = $citiesWithProviders
            ->pluck("provider_name")
            ->countBy()
            ->map(function ($count, $name) {
                return [
                    "name" => $name,
                    "count" => $count,
                ];
            })
            ->sortByDesc("count")
            ->values()
            ->all();

        $providers = ProviderResource::collection(Provider::all());

        return response()->json([
            "usersCount" => $usersCount,
            "citiesWithProvidersCount" => $citiesWithProvidersCount,
            "countriesWithCitiesWithProvidersCount" => $countriesWithCitiesWithProvidersCount,
            "providersCount" => $providersCount,
            "providerCitiesCount" => $providerCitiesCount,
            "providers" => $providers,
        ]);

    }
}
