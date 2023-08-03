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
    protected int $importInfoId;

    public function __construct(
        protected Client $client,
    ) {}

    public function setImportInfo(int $importInfoId): static
    {
        $this->importInfoId = $importInfoId;

        return $this;
    }

    abstract public function extract(): static;

    abstract public function transform(): void;

    public function hasStoppedExecution(): bool
    {
        return $this->stopExecution;
    }

    public static function getProviderName(): string
    {
        $parted = explode("\\", static::class);
        $parted = str_replace("DataImporter", "", $parted[count($parted) - 1]);
        $classNameParts = explode("@", $parted);

        return $classNameParts[0];
    }

    protected function countryNotFound(string $cityName, string $countryName): void {
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

    protected function createProvider(int $cityId, string $providerName): void
    {
        CityProvider::query()->updateOrCreate([
            "city_id" => $cityId,
            "provider_name" => $providerName,
            "created_by" => "scrapper",
        ]);
    }

    protected function deleteMissingProviders(string $providerName, array $existingCityProviders): void {
        $cityProvidersToDelete = CityProvider::query()
            ->where("provider_name", $providerName)
            ->whereNotIn("city_id", $existingCityProviders)
            ->whereNot("created_by", "admin")
            ->get();
        $cityProvidersToDelete->each(fn($cityProvider) => $cityProvider->delete());
    }

    protected function createImportInfoDetails(string $code, string $providerName): void {
        ImportInfoDetail::query()->updateOrCreate(
            [
                "provider_name" => $providerName,
                "import_info_id" => $this->importInfoId,
                "code" => $code,
            ],
            [
                "provider_name" => $providerName,
                "import_info_id" => $this->importInfoId,
                "code" => $code,
            ],
        );
    }
}
