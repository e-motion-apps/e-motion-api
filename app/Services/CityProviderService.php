<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ChangeInFavouriteCityEnum;
use App\Events\ChangeInFavoriteCityEvent;
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

            if ($provider->wasRecentlyCreated) {
                event(new ChangeInFavoriteCityEvent($city->id, $providerName, ChangeInFavouriteCityEnum::Added));
            }
            $existingCityProviderNames[] = $providerName;
        }

        $cityProvidersToDelete = CityProvider::query()
            ->where("city_id", $city->id)
            ->whereNotIn("provider_name", $existingCityProviderNames)
            ->get();

        foreach ($cityProvidersToDelete as $cityProvider) {
            $cityProvider->delete();
            event(new ChangeInFavoriteCityEvent($cityProvider->city_id, $cityProvider->provider_name, ChangeInFavouriteCityEnum::Removed));
        }
    }
}
