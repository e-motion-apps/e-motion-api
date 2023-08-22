<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CityRequest;
use App\Http\Requests\ProviderRequest;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\ProviderResource;
use App\Models\City;
use App\Models\Country;
use App\Models\Provider;
use Inertia\Inertia;
use Inertia\Response;

class ProviderController extends Controller
{
    public function index(): Response
    {
      //  $cities = City::all();

        $providers = Provider::query()
            ->paginate(15)
            ->withQueryString();
      //  $countries = Country::all();

        return Inertia::render("Providers/Index", [
         //   "cities" => CityResource::collection($cities),
            "providers" => ProviderResource::collection($providers),
         //   "countries" => CountryResource::collection($countries),
        ]);
    }

    public function store(ProviderRequest $request): void
    {
        Provider::query()->create($request->validated());
    }

    public function update(ProviderRequest $request, Provider $provider): void
    {
        $provider->update($request->validated());
    }

    public function destroy(Provider $provider): void
    {
        $provider->delete();
    }
}
