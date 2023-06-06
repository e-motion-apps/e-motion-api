<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CountryRequest;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use App\Services\CountryService;
use Inertia\Inertia;
use Inertia\Response;

class CountryController extends Controller
{
    public function index(CountryService $service): Response
    {
        $countries = $service->indexCountry();

        return Inertia::render("Countries", [
            "countries" => $countries,
        ]);
    }

    public function store(CountryRequest $request, CountryService $service): void
    {
        $service->storeCountry(
            $request->name,
            $request->altName,
            $request->lat,
            $request->lon,
            $request->iso,
        );
    }

    public function show(Country $country, CountryService $service): CountryResource
    {
        return $service->showCountry($country);
    }

    public function update(CountryService $service, Country $country, CountryRequest $request): void
    {
        $service->updateCountry(
            $country,
            $request->name,
            $request->altName,
            $request->lat,
            $request->lon,
            $request->iso,
        );
    }

    public function destroy(Country $country, CountryService $service): void
    {
        $service->destroyCountry($country);
    }
}
