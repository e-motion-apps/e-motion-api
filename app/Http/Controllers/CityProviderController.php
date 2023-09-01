<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CityProviderRequest;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\ProviderResource;
use App\Models\City;
use App\Models\Country;
use App\Models\Provider;
use App\Services\CityProviderService;
use App\Services\DataImporterService;

class CityProviderController extends Controller
{
    public function index(): array
    {
        $cities = CityResource::collection(
            City::with("cityAlternativeNames", "cityProviders", "country")
                ->has("cityProviders")
                ->whereHas("cityProviders", function ($query): void {
                    $query->whereNotNull("latitude")->whereNotNull("longitude");
                })
                ->get()
                ->sortBy("name")
                ->sortByDesc(fn(City $city): int => $city->cityProviders->count()),
        );

        $providers = ProviderResource::collection(Provider::all()->sortBy("name"));
        $countries = Country::whereHas("cities.cityProviders")
            ->with(["cities.cityAlternativeNames", "cities.cityProviders"])
            ->get()
            ->sortBy("name");

        $countries = CountryResource::collection($countries);

        return [
            "cities" => $cities,
            "providers" => $providers,
            "countries" => $countries,
        ];
    }

    public function update(CityProviderService $service, CityProviderRequest $request, City $city): void
    {
        $service->updateProvider($request->providerNames, $city);
    }

    public function runImporters(DataImporterService $service): void
    {
        $service->run();
    }
}
