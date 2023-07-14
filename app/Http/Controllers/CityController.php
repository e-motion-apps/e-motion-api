<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CityRequest;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\ProviderResource;
use App\Models\City;
use App\Models\Country;
use App\Models\Provider;
use Inertia\Inertia;
use Inertia\Response;

class CityController extends Controller
{
    public function index(): Response
    {
        $cities = CityResource::collection(City::with("cityAlternativeName", "cityProvider", "country")->get()->sortBy("country_id"));
        $providers = ProviderResource::collection(Provider::all());
        $countries = CountryResource::collection(Country::all());

        return Inertia::render("Cities/Index", [
            "cities" => $cities,
            "providers" => $providers,
            "countries" => $countries,
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
