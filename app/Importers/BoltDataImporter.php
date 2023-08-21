<?php

declare(strict_types=1);

namespace App\Importers;

use GuzzleHttp\Exception\GuzzleException;

class BoltDataImporter extends DataImporter
{
    protected array $fetchedCities = [];
    protected array $fetchedCityDictionary = [];
    protected array $fetchedCountriesDictionary = [];

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://bolt.eu/page-data/en/scooters/page-data.json");
            $content = json_decode($response->getBody()->getContents(), true);

            $this->fetchedCountriesDictionary = json_decode($content["result"]["data"]["countries"]["edges"][0]["node"]["data"], associative: true)["countries"];
            $this->fetchedCityDictionary = $content["result"]["data"]["cities"]["nodes"];
            $this->fetchedCities = $content["result"]["data"]["scooterCities"]["nodes"];

            if (empty($this->fetchedCountriesDictionary) || empty($this->fetchedCityDictionary) || empty($this->fetchedCities)) {
                $this->createImportInfoDetails("204", self::getProviderName());
                $this->stopExecution = true;
            }
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());
            $this->stopExecution = true;
        }

        return $this;
    }

    public function transform(): void
    {
        if ($this->stopExecution) {
            return;
        }

        $fetchedCityDictionary = [];

        foreach ($this->fetchedCityDictionary as $city) {
            $fetchedCityDictionary[$city["slug"]] = $city;
        }

        $existingCityProviders = [];

        foreach ($this->fetchedCities as $city) {
            if ($city["city"]) {
                $fetched = $fetchedCityDictionary[$city["city"]] ?? null;

                if ($fetched === null) {
                    continue;
                }

                $countryName = $this->fetchedCountriesDictionary[$fetched["country"]["countryCode"]]["name"] ?? "Poland";
                $cityName = $fetched["name"] ?? $city["city"];
            }
            $provider = $this->load($cityName, $countryName);

            if ($provider !== "") {
                $existingCityProviders[] = $provider;
            }
        }

        unset($fetchedCities);
        unset($fetchedCityDictionary);
        unset($fetchedCountriesDictionary);

        $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
    }
}
