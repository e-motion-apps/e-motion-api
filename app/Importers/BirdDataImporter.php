<?php

declare(strict_types=1);

namespace App\Importers;

use App\Exceptions\MapboxGeocodingServiceException;
use App\Models\City;
use App\Models\CityAlternativeName;
use App\Models\Country;
use GuzzleHttp\Exception\GuzzleException;

class BirdDataImporter extends DataImporter
{
    protected string $html;

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://www.bird.co/map/");
            $this->html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());
            $this->stopExecution = true;
        }

        return $this;
    }

    /**
     * @throws MapboxGeocodingServiceException
     */
    public function transform(): void
    {
        if ($this->stopExecution) {
            return;
        }

        $existingCityProviders = [];
        $coordinatesList = $this->parseData($this->html);

        foreach ($coordinatesList as $coordinates) {
            if ($coordinates) {
                [$lat, $long] = explode(" ", $coordinates);

                [$cityName, $countryName] = $this->mapboxService->getPlaceFromApi($lat, $long);

                $provider = $this->load($cityName, $countryName, $lat, $long);

                if ($provider !== "") {
                    $existingCityProviders[] = $provider;
                }
            }
        }
        $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
    }

    protected function parseData(string $html): array
    {
        $pattern = '/let features = \[([\s\S]*?)\];/';

        if (preg_match($pattern, $html, $matches)) {
            $fetchedData = $matches[1];
        }

        if (!isset($fetchedData)) {
            $this->createImportInfoDetails("204", self::getProviderName());
            $this->stopExecution = true;
        }

        $fetchedData = str_replace(["{", "}", "\t", "type: 'hq'", ",", "\n", "position: new google.maps.LatLng("], "", $fetchedData);
        $coordinates = explode(")", $fetchedData);
        $coordinates = array_filter($coordinates, "trim");

        return array_map("trim", $coordinates);
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
        $country = Country::query()->where("name", $countryName)->first();

        if ($country) {
            $city = City::query()->create([
                "name" => $cityName,
                "latitude" => $lat,
                "longitude" => $long,
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
