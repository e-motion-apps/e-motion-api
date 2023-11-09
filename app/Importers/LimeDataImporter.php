<?php

declare(strict_types=1);

namespace App\Importers;

use App\Models\City;
use App\Models\CityAlternativeName;
use App\Models\Country;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class LimeDataImporter extends DataImporter
{
    protected Crawler $sections;

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://www.li.me/locations");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());
            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);
        $this->sections = $crawler->filter(".pb-4 > .box-content .inline-block");

        if (count($this->sections) === 0) {
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

        foreach ($this->sections as $section) {
            $cityName = trim($section->nodeValue);
            $countryName = trim($section->parentNode->parentNode->previousSibling->previousSibling->nodeValue);

            $provider = $this->load($cityName, $countryName);

            if ($provider !== "") {
                $existingCityProviders[] = $provider;
            }
        }
        $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
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
        switch ($countryName) {
            case str_contains($countryName, "US"):
                $country = Country::query()->where("name", "United States")->first();

                break;
            default:
                $country = Country::query()->where("name", $countryName)->orWhere("alternative_name", $countryName)->first();

                break;
        }

        if ($country) {
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

            $this->createProvider($city->id, self::getProviderName());

            return strval($city->id);
        }
        $this->countryNotFound($cityName, $countryName);
        $this->createImportInfoDetails("420", self::getProviderName());

        return "";
    }
}
