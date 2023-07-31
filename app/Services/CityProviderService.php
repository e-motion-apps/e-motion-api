<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\City;
use App\Models\CityProvider;

class CityProviderService
{
    public function updateProvider(array $providerNames, City $city): void
    {
        $existingCityProviderNames = [];

        foreach ($providerNames as $providerName) {
            $provider = CityProvider::query()
                ->updateOrCreate([
                    "city_id" => $city->id,
                    "provider_name" => $providerName,
                ]);

            if ($provider->created_by !== "scrapper") {
                CityProvider::query()->where([
                    "city_id" => $city->id,
                    "provider_name" => $providerName,
                ])->update([
                    "created_by" => "admin",
                ]);
            }

            $existingCityProviderNames[] = $providerName;
        }

        $cityProvidersToDelete = CityProvider::query()
            ->where("city_id", $city->id)
            ->whereNotIn("provider_name", $existingCityProviderNames)
            ->get();

        $cityProvidersToDelete->each(fn($cityProvider) => $cityProvider->delete());
    }
}
