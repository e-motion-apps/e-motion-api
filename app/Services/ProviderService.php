<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\City;
use App\Models\Provider;

class ProviderService
{
    public function updateProvider(array $providers, City $city): void
    {
        $existingProviderIds = [];

        foreach ($providers as $providerId) {
            $provider = Provider::query()
                ->updateOrCreate([
                    "city_id" => $city->id,
                    "provider_list_id" => $providerId,
                ]);

            if ($provider->created_by !== "scrapper") {
                Provider::query()->where([
                    "city_id" => $city->id,
                    "provider_list_id" => $providerId,
                ])->update([
                    "created_by" => "admin",
                ]);
            }

            $existingProviderIds[] = $providerId;
        }

        $providersToDelete = Provider::query()
            ->where("city_id", $city->id)
            ->whereNotIn("provider_list_id", $existingProviderIds)
            ->get();

        $providersToDelete->each(fn($provider) => $provider->delete());
    }
}
