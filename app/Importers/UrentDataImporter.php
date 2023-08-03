<?php

declare(strict_types=1);

namespace App\Importers;

use App\Models\City;
use App\Models\CityAlternativeName;
use App\Models\Country;
use App\Services\MapboxGeocodingService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Symfony\Component\DomCrawler\Crawler;

class UrentDataImporter extends DataImporter
{
    private const COUNTRY_NAME = "Russia";

    protected Crawler $sections;
    protected MapboxGeocodingService $mapboxService;

    public function __construct(Client $client, MapboxGeocodingService $mapboxService)
    {
        parent::__construct($client);
        $this->mapboxService = $mapboxService;
    }

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://start.urent.ru/");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());

            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);
        $this->sections = $crawler->filter(".city-block-1 > div > div > .grid-item-1 > ul > li");

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
            $cityName = $section->nodeValue;

            if (preg_match('/\p{Cyrillic}/u', $cityName) === 1){
                $tr = new GoogleTranslate("en");
                $cityName = $tr->translate($cityName);
            }
            $cityName = str_replace("-", " ", $cityName);

            $city = City::query()->where("name", $cityName)->first();
            $alternativeCity = CityAlternativeName::query()->where("name", $cityName)->first();

            if ($city || $alternativeCity) {
                $cityId = $city ? $city->id : $alternativeCity->city_id;

                $this->createProvider($cityId, self::getProviderName());
                $existingCityProviders[] = $cityId;
            }
            else {
                $country = Country::query()->where("name", self::COUNTRY_NAME)->orWhere("alternative_name", self::COUNTRY_NAME)->first();

                if ($country) {
                    $coordinates = $this->mapboxService->getCoordinatesFromApi($cityName, self::COUNTRY_NAME);

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
                    $this->countryNotFound($cityName, self::COUNTRY_NAME);
                    $this->createImportInfoDetails("420", self::getProviderName());
                }
            }
        }
        $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
    }
}
