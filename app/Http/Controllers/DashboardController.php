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
        $userCount = User::count();

        $cityWithProviders = CityProvider::distinct("city_id")->get();
        $cityId = $cityWithProviders->unique()->pluck("city_id");
        $cityIdCount = $cityId->count();
        
        $countryId = City::whereIn("id", $cityId)->distinct()->pluck("country_id");
        $countryIdCount = $countryId->count();
        $countriesWithCitiesWithProviders = Country::whereIn("id", $countryId)->count();

        return Inertia::render("Dashboard/Index", [
            "userCount" => $userCount,
            "cityWithProviders" => $cityWithProviders,
            "cityCount" => $cityIdCount,
            "countryCount" => $countryIdCount,
            "countriesWithCitiesWithProvidersCount" => $countriesWithCitiesWithProviders,
        ]);
    }
}
