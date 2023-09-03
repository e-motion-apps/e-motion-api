<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\CityProvider;
use App\Models\Country;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $usersCount = User::count();

        $citiesWithProviders = CityProvider::distinct("city_id")->get();
        $citiesWithProvidersCount = $citiesWithProviders->count();

        $citiesWithProvidersIds = $citiesWithProviders->pluck("city_id");
        $countriesWithCitiesWithProvidersIds = City::whereIn("id", $citiesWithProvidersIds)->distinct()->pluck("country_id");
        $countriesWithCitiesWithProvidersCount = Country::whereIn("id", $countriesWithCitiesWithProvidersIds)->count();

        return Inertia::render("Dashboard/Index", [
            "usersCount" => $usersCount,
            "citiesWithProvidersCount" => $citiesWithProvidersCount,
            "countriesWithCitiesWithProvidersCount" => $countriesWithCitiesWithProvidersCount,
        ]);
    }
}
