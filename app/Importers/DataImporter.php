<?php

declare(strict_types=1);

namespace App\Importers;

use App\Models\City;
use App\Models\CityAlternativeName;
use App\Models\CityProvider;
use App\Models\CityWithoutAssignedCountry;
use App\Models\Country;
use App\Models\ImportInfoDetail;
use App\Services\MapboxGeocodingService;
use GuzzleHttp\Client;
use Stichoza\GoogleTranslate\GoogleTranslate;

abstract class DataImporter
{
    protected bool $stopExecution = false;
    protected int $importInfoId;
    protected GoogleTranslate $translate;

    public function __construct(
        protected Client $client,
        protected MapboxGeocodingService $mapboxService,
    ) {
        $this->translate = new GoogleTranslate();
    }

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

    public function translate(string $word, $language): string
    {
        return $this->translate->setTarget($language)->translate($word);
    }

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

    protected function createProvider(int $cityId, string $providerName): void
    {
        CityProvider::query()->updateOrCreate([
            "city_id" => $cityId,
            "provider_name" => $providerName,
            "created_by" => "scrapper",
        ]);
    }

    protected function deleteMissingProviders(string $providerName, array $existingCityProviders): void
    {
        $cityProvidersToDelete = CityProvider::query()
            ->where("provider_name", $providerName)
            ->whereNotIn("city_id", $existingCityProviders)
            ->whereNot("created_by", "admin")
            ->get();
        $cityProvidersToDelete->each(fn($cityProvider) => $cityProvider->delete());
    }

    protected function createImportInfoDetails(string $code, string $providerName): void
    {
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

    protected function load(string $cityName, string $countryName, string $lat = "", string $long = ""): string
    {
        $city = City::query()->where("name", $cityName)->first();
        $alternativeCityName = CityAlternativeName::query()->where("name", $cityName)->first();

        if ($city || $alternativeCityName) {
            $cityId = $city ? $city->id : $alternativeCityName->city_id;

            $this->createProvider($cityId, self::getProviderName());

            return strval($cityId);
        }
        $country = Country::query()->where("name", $countryName)->orWhere("alternative_name", $countryName)->first();

        if ($country) {
            $coordinates = $this->mapboxService->getCoordinatesFromApi($cityName, $countryName);
            $countCoordinates = count($coordinates);

            if (!$countCoordinates) {
                $this->createImportInfoDetails("419", self::getProviderName());
            }

            $city = City::query()->create([
                "name" => $cityName,
                "latitude" => ($countCoordinates > 0) ? $coordinates[0] : null,
                "longitude" => ($countCoordinates > 0) ? $coordinates[1] : null,
                "country_id" => $country->id,
            ]);

            $this->createProvider($city->id, self::getProviderName());

            return strval($city->id);
        }
        $this->countryNotFound($cityName, $countryName);
        $this->createImportInfoDetails("420", self::getProviderName());

        return "";
    }
}
