<?php

namespace App\Importers;

use App\Models\CityWithoutAssignedCountry;
use App\Models\ImportInfoDetail;
use App\Models\Provider;

abstract class DataImporter
{
    protected bool $stopExecution = false;
    protected int $importInfoId;

    public function __construct(int $importInfoId)
    {
        $this->importInfoId = $importInfoId;
    }

    protected function countryNotFound(string $cityName, string $countryName): void {
        CityWithoutAssignedCountry::query()->updateOrCreate(
            [
                'city_name' => $cityName,
                'country_name' => $countryName,
            ],
            [
                'city_name' => $cityName,
                'country_name' => $countryName,
            ]
        );
    }

    protected function createProvider(int $cityId, int $providerListId): void
    {
        Provider::query()->updateOrCreate([
            "city_id" => $cityId,
            "provider_list_id" => $providerListId,
            "created_by" => "scrapper",
        ]);
    }

    protected function deleteMissingProviders(int $providerListId, array $existingProviders) {
        $providersToDelete = Provider::query()
            ->where("provider_list_id", $providerListId)
            ->whereNotIn("city_id", $existingProviders)
            ->whereNot("created_by", "admin")
            ->get();
        $providersToDelete->each(fn($provider) => $provider->delete());
    }

    protected function createImportInfoDetails(string $code, int $providerListId) {
        ImportInfoDetail::query()->updateOrCreate(
            [
                "provider_id" => $providerListId,
                "import_id" => $this->importInfoId,
                "code" => $code
            ],
            [
                "provider_id" => $providerListId,
                "import_id" => $this->importInfoId,
                "code" => $code
            ]
        );
    }

    abstract public function extract(): static;

    abstract public function transform(): void;
}
