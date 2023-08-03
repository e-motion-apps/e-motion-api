<?php

declare(strict_types=1);

namespace App\Importers;

use App\Models\City;
use App\Models\CityAlternativeName;
use App\Models\Country;
use App\Services\MapboxGeocodingService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class NeuronDataImporter extends DataImporter
{
    protected array $regionsData;
    protected MapboxGeocodingService $mapboxService;

    public function __construct(Client $client, MapboxGeocodingService $mapboxService)
    {
        parent::__construct($client);
        $this->mapboxService = $mapboxService;
    }

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://www.scootsafe.com/");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());

            $this->stopExecution = true;

            return $this;
        }
        $pattern = '/regions\s*=\s*({.*?})\s*;/s';

        if (preg_match($pattern, $html, $matches)) {
            $jsonString = $matches[1];
            $this->regionsData = json_decode($jsonString, associative: true);
        }

        if (!isset($this->regionsData["list"])) {
            $this->createImportInfoDetails("204", self::getProviderName());

            $this->stopExecution = true;
        }

        return $this;
    }

    public function transform(): void
    {
        if ($this->stopExecution) {
            return;
        }

        $existingCityProviders = [];

        $regionsList = $this->regionsData["list"];

        foreach ($regionsList as $region) {
            $countryName = $region["name"];

            foreach ($region["cities"] as $city) {
                $cityName = $city["name"];

                $cityDB = City::query()->where("name", $cityName)->first();
                $alternativeCityNameDB = CityAlternativeName::query()->where("name", $cityName)->first();

                if ($cityDB || $alternativeCityNameDB) {
                    $cityId = $cityDB ? $cityDB->id : $alternativeCityNameDB->city_id;

                    $this->createProvider($cityId, self::getProviderName());
                    $existingCityProviders[] = $cityId;
                } else {
                    $country = Country::query()->where("name", $countryName)->first();

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
                        $existingCityProviders[] = $city->id;
                    } else {
                        $this->countryNotFound($cityName, $countryName);
                        $this->createImportInfoDetails("420", self::getProviderName());
                    }
                }
            }
        }
        $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
    }
}
