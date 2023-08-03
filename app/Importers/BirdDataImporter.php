<?php

declare(strict_types=1);

namespace App\Importers;

use App\Exceptions\MapboxGeocodingServiceException;
use App\Models\City;
use App\Models\CityAlternativeName;
use App\Models\Country;
use App\Services\MapboxGeocodingService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BirdDataImporter extends DataImporter
{
    protected string $html;

    public function __construct(
        Client $client,
        protected MapboxGeocodingService $mapboxService,
    )
    {
        parent::__construct($client);
    }

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
        $existingCityProviders = [];
        $coordinatesList = $this->parseData($this->html);

        if ($this->stopExecution) {
            return;
        }

        foreach ($coordinatesList as $coordinates) {
            if ($coordinates) {
                [$lat, $long] = explode(" ", $coordinates);

                [$cityName, $countryName] = $this->mapboxService->getPlaceFromApi($lat, $long);

                $provider = $this->save($cityName, $countryName, $lat, $long);

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

    protected function save(string $cityName, string $countryName, string $lat, string $long): string
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
