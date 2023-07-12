<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProviderRequest;
use App\Http\Resources\CityResource;
use App\Http\Resources\ProviderResource;
use App\Models\City;
use App\Models\ProviderList;
use App\Services\DataImporterService;
use App\Services\ProviderService;

class ProviderController extends Controller
{
    public function index(): array
    {
        $cities = CityResource::collection(City::with("cityAlternativeName", "provider", "country")
            ->get()
            ->sortByDesc(fn($city) => $city->provider->count()));
        $providers = ProviderResource::collection(ProviderList::all());

        return [
            "cities" => $cities,
            "providers" => $providers,
        ];
    }

    public function update(ProviderService $service, ProviderRequest $request, City $city): void
    {
        $service->updateProvider($request->providers, $city);
    }

    public function runImporters(DataImporterService $service): void
    {
        $service->run();
    }
}
