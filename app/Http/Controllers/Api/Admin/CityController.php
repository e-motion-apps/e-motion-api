<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityRequest;
use App\Http\Resources\CityResource;
use App\Http\Resources\CityWithoutAssignedCountryResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\ProviderResource;
use App\Models\City;
use App\Models\CityWithoutAssignedCountry;
use App\Models\Country;
use App\Models\Provider;
use Illuminate\Http\JsonResponse;

class CityController extends Controller
{
    public function index(): JsonResponse
    {
        $cities = City::query()
            ->with("cityAlternativeNames", "cityProviders", "country")
            ->orderByProvidersCount()
            ->searchCityNames()
            ->orderByName()
            ->orderByCountry()
            ->orderByTimeRange()
            ->orderByEmptyCoordinates()
            ->paginate(15)
            ->withQueryString();

        $providers = Provider::all();
        $countries = Country::all();

        $citiesWithoutAssignedCountry = CityWithoutAssignedCountry::all();

        return response()->json([
            "cities" => CityResource::collection($cities),
            "providers" => ProviderResource::collection($providers),
            "countries" => CountryResource::collection($countries),
            "citiesWithoutAssignedCountry" => CityWithoutAssignedCountryResource::collection($citiesWithoutAssignedCountry),
        ]);
    }

    public function store(CityRequest $request): void
    {
        City::query()->create($request->validated());
    }

    public function update(CityRequest $request, City $city): void
    {
        $city->update($request->validated());
    }

    public function destroy(City $city): void
    {
        $city->delete();
    }
}
