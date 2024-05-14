<?php

declare(strict_types=1);

namespace App\Importers;

use App\Enums\ChangeInFavoriteCityEnum;
use App\Enums\ServicesEnum;
use App\Events\ChangeInFavoriteCityEvent;
use App\Models\City;
use App\Models\CityAlternativeName;
use App\Models\CityProvider;
use App\Models\CityWithoutAssignedCountry;
use App\Models\Country;
use App\Models\ImportInfoDetail;
use App\Models\Service;
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

    public function translate(string $word, string $language): string
    {
        return $this->translate->setTarget($language)->translate($word);
    }

    protected function countryNotFound(string $cityName, string $countryName): void
    {
        $cityAttributes = [
            "city_name" => $cityName,
            "country_name" => $countryName,
        ];

        $city = CityWithoutAssignedCountry::query()->withTrashed()->updateOrCreate(
            $cityAttributes,
            $cityAttributes,
        );

        if ($city->wasRecentlyCreated) {
            $this->createImportInfoDetails("420", self::getProviderName());
        }
    }

    protected function createProvider(int $cityId, string $providerName, array $services): void
    {
        foreach ($services as $service) {
            $service = Service::query()->where("type", $service)->first();

            if (!CityProvider::query()->where("city_id", $cityId)->where("provider_name", $providerName)->exists()) {
                event(new ChangeInFavoriteCityEvent($cityId, $providerName, ChangeInFavoriteCityEnum::Added));
            }
            CityProvider::query()->updateOrCreate([
                "provider_name" => $providerName,
                "city_id" => $cityId,
                "created_by" => "scraper",
                "service_id" => $service->id,
            ]);
        }
    }

    protected function deleteMissingProviders(string $providerName, array $existingCityProviders): void
    {
        $cityProvidersToDelete = CityProvider::query()
            ->where("provider_name", $providerName)
            ->whereNotIn("city_id", $existingCityProviders)
            ->whereNot("created_by", "admin")
            ->get();

        foreach ($cityProvidersToDelete as $cityProvider) {
            event(new ChangeInFavoriteCityEvent($cityProvider->city_id, $providerName, ChangeInFavoriteCityEnum::Removed));
        }

        $cityProvidersToDelete->each(fn(CityProvider $cityProvider) => $cityProvider->delete());
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

    protected function load(string $countryName, string $cityName, array $services = [ServicesEnum::Escooter], string $lat = "", string $long = ""): string
    {
        $country = Country::query()->where("name", $countryName)->orWhere("alternative_name", $countryName)->first();

        if ($country) {
            $city = City::query()->where("name", $cityName)->where("country_id", $country->id)->first();
            $alternativeCityName = CityAlternativeName::query()->where("name", $cityName)->first();

            if ($city) {
                $cityId = $city->id;
                $this->createProvider($cityId, self::getProviderName(), $services);

                return strval($cityId);
            } elseif ($alternativeCityName) {
                $cityId = $alternativeCityName->city_id;
                $city = City::query()->where("id", $cityId)->first();

                if ($city->country_id === $country->id) {
                    $this->createProvider($cityId, self::getProviderName(), $services);
                }
            }

            $coordinates = $this->mapboxService->getCoordinatesFromApi($cityName, $countryName);
            $countCoordinates = count($coordinates);

            if ($countCoordinates === 0 || $coordinates[0] === null || $coordinates[1] === null) {
                $this->createImportInfoDetails("419", self::getProviderName());
            }

            $city = City::query()->create([
                "name" => $cityName,
                "latitude" => $coordinates[0] ?? null,
                "longitude" => $coordinates[1] ?? null,
                "country_id" => $country->id,
            ]);

            $this->createProvider($city->id, self::getProviderName(), $services);

            return strval($city->id);
        }
        $this->countryNotFound($cityName, $countryName);

        return "";
    }
}
