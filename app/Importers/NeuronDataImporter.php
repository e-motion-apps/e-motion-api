<?php

declare(strict_types=1);

namespace App\Importers;
require_once 'vendor/autoload.php';

use App\Models\City;
use App\Models\CityAlternativeName;
use App\Models\Country;
use App\Services\MapboxGeocodingService;
use Symfony\Component\DomCrawler\Crawler;
use Throwable;

class NeuronDataImporter extends DataImporter
{
    private const PROVIDER_ID = 9;

    protected Crawler $sections;
    protected string $iso;
    protected $jsonData;

    public function extract(): static
    {
        $this->stopExecution = false;
        try {
            $html = file_get_contents("https://www.scootsafe.com/");
        } catch (Throwable) {
            $this->createImportInfoDetails("400", self::PROVIDER_ID);

            $this->stopExecution = true;

            return $this;
        }

        $pattern = '/{"list":\[.*?};/';
        preg_match($pattern, $html, $matches);

        $this->jsonData = json_decode($matches[0], true);

        if (!$this->jsonData) {
            $this->createImportInfoDetails("204", self::PROVIDER_ID);

            $this->stopExecution = true;
        }

        return $this;
    }

    public function transform(): void
    {
        if ($this->stopExecution) {
            return;
        }

        $mapboxService = new MapboxGeocodingService();
        $existingCityProviders = [];

        $countries=$this->jsonData["list"];
        foreach ($countries as $country) {
            $countryName = $country["name"];
            $cities = $country["cities"];
            foreach ($cities as $city){
                $cityName = $city["name"];

                $city = City::query()->where("name", $cityName)->first();
                $alternativeCityName = CityAlternativeName::query()->where("name", $cityName)->first();

                if ($city || $alternativeCityName) {
                    $cityId = $city ? $city->id : $alternativeCityName->city_id;

                    $this->createProvider($cityId, self::PROVIDER_ID);
                    $existingCityProviders[] = $cityId;
                } else {
                    $country = Country::query()->where("name", $countryName)->first();

                    if ($country) {
                        $coordinates = $mapboxService->getCoordinatesFromApi($cityName, $countryName);
                        $countCoordinates = count($coordinates);

                        if (!$countCoordinates) {
                            $this->createImportInfoDetails("419", self::PROVIDER_ID);
                        }

                        $city = City::query()->create([
                            "name" => $cityName,
                            "latitude" => ($countCoordinates > 0) ? $coordinates[0] : null,
                            "longitude" => ($countCoordinates > 0) ? $coordinates[1] : null,
                            "country_id" => $country->id,
                        ]);

                        $this->createProvider($city->id, self::PROVIDER_ID);
                        $existingCityProviders[] = $city->id;
                    } else {
                        $this->countryNotFound($cityName, $countryName);
                        $this->createImportInfoDetails("420", self::PROVIDER_ID);
                    }
                }
            }
        }
        $this->deleteMissingProviders(self::PROVIDER_ID, $existingCityProviders);
    }
}
