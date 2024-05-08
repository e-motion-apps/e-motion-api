<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityProviderRequest;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\ProviderResource;
use App\Models\City;
use App\Models\Country;
use App\Models\Provider;
use App\Services\CityProviderService;
use App\Services\DataImporterService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CityProviderController extends Controller
{
    public function index(): JsonResponse
    {
        $cities = CityResource::collection(
            City::with("cityAlternativeNames", "cityProviders", "country")
                ->has("cityProviders")
                ->whereHas("cityProviders", fn($query): Builder => $query->whereNotNull("latitude")->whereNotNull("longitude"))
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

        return response()->json([
            "cities" => $cities,
            "providers" => $providers,
            "countries" => $countries,
        ]);
    }

    public function update(CityProviderService $service, CityProviderRequest $request, City $city): JsonResponse
    {
        foreach ($request->providerNames as $providerName) {
            if (Provider::query()->where("name", $providerName)->doesntExist()) {
                return response()->json([
                    "message" => __("Provider does not exist."),
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
        $service->updateProvider($request->providerNames, $city);

        return response()->json([
            "message" => __("City providers updated successfully."),
        ]);
    }

    public function runImporters(DataImporterService $service): JsonResponse
    {
        $service->run();

        return response()->json([
            "message" => __("Importers started."),
        ]);
    }
}
