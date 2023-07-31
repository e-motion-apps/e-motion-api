<?php

declare(strict_types=1);

namespace App\Importers;

use App\Models\CityProvider;
use App\Models\CityWithoutAssignedCountry;
use App\Models\ImportInfoDetail;
use GuzzleHttp\Client;

abstract class DataImporter
{
    protected bool $stopExecution = false;
    protected Client $client;
    protected int $importInfoId;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function setImportInfo(int $importInfoId): static
    {
        $this->importInfoId = $importInfoId;

        return $this;
    }

    abstract public function extract(): static;

    abstract public function transform(): void;

    protected function countryNotFound(string $cityName, string $countryName): void
    {
        CityWithoutAssignedCountry::query()->updateOrCreate(
            [
                "city_name" => $cityName,
                "country_name" => $countryName,
            ],
            [
                "city_name" => $cityName,
                "country_name" => $countryName,
            ],
        );
    }

    protected function createProvider(int $cityId, int $providerId): void
    {
        CityProvider::query()->updateOrCreate([
            "city_id" => $cityId,
            "provider_id" => $providerId,
            "created_by" => "scrapper",
        ]);
    }

    protected function deleteMissingProviders(int $providerId, array $existingCityProviders): void
    {
        $cityProvidersToDelete = CityProvider::query()
            ->where("provider_id", $providerId)
            ->whereNotIn("city_id", $existingCityProviders)
            ->whereNot("created_by", "admin")
            ->get();
        $cityProvidersToDelete->each(fn($cityProvider) => $cityProvider->delete());
    }

    protected function createImportInfoDetails(string $code, int $providerId): void
    {
        ImportInfoDetail::query()->updateOrCreate(
            [
                "provider_id" => $providerId,
                "import_info_id" => $this->importInfoId,
                "code" => $code,
            ],
            [
                "provider_id" => $providerId,
                "import_info_id" => $this->importInfoId,
                "code" => $code,
            ],
        );
    }
}
