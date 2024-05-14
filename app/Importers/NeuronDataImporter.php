<?php

declare(strict_types=1);

namespace App\Importers;

use GuzzleHttp\Exception\GuzzleException;

class NeuronDataImporter extends DataImporter
{
    protected array $regionsData;

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

                $provider = $this->load($countryName, $cityName);

                if ($provider !== "") {
                    $existingCityProviders[] = $provider;
                }
            }
        }
        $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
    }
}
