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
            Provider::query()
                ->updateOrCreate([
                    "provider_id" => $providerId,
                    "city_id" => $city->id,
                ])
                ->toArray();
            $existingProviderIds[] = $providerId;
        }

        $providersToDelete = Provider::query()
            ->where("city_id", $city->id)
            ->whereNotIn("provider_id", $existingProviderIds)
            ->get();

        $providersToDelete->each(function ($provider): void {
            $provider->delete();
        });
    }
}
