<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CountryService
{
    public function indexCountry(): AnonymousResourceCollection
    {
        return CountryResource::collection(Country::all()->sortBy("name"));
    }

    public function storeCountry(string $name, ?string $altName, string $lat, string $lon, string $iso): void
    {
        Country::create([
            "name" => $name,
            "altName" => $altName,
            "lat" => $lat,
            "lon" => $lon,
            "iso" => $iso,
        ]);
    }

    public function showCountry(Country $country): CountryResource
    {
        return CountryResource::make($country);
    }

    public function updateCountry(Country $country, string $name, ?string $altName, string $lat, string $lon, string $iso): void
    {
        $country->update([
            "name" => $name,
            "altName" => $altName,
            "lat" => $lat,
            "lon" => $lon,
            "iso" => $iso,
        ]);
    }

    public function destroyCountry(Country $country): void
    {
        $country->delete();
    }
}
