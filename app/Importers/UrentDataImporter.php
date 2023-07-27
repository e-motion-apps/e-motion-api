<?php

declare(strict_types=1);

namespace App\Importers;

use App\Models\City;
use App\Models\CityAlternativeName;
use App\Models\Country;
use App\Services\MapboxGeocodingService;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Symfony\Component\DomCrawler\Crawler;
use Throwable;

class UrentDataImporter extends DataImporter
{
    private const PROVIDER_ID = 15;
    private const COUNTRY_NAME = "Russia";

    protected Crawler $sections;

    public function extract(): static
    {
        try {
            $html = file_get_contents("https://start.urent.ru/");
        } catch (Throwable) {
            $this->createImportInfoDetails("400", self::PROVIDER_ID);

            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);
        $this->sections = $crawler->filter(".city-block-1 > div > div > .grid-item-1 > ul > li");

        if (count($this->sections) === 0) {
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

                $this->createProvider($cityId, self::PROVIDER_ID);
                $existingCityProviders[] = $cityId;
            }
            else {
                $country = Country::query()->where("name", self::COUNTRY_NAME)->orWhere("alternative_name", self::COUNTRY_NAME)->first();

                if ($country) {
                    $coordinates = $mapboxService->getCoordinatesFromApi($cityName, self::COUNTRY_NAME);

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
                    $this->countryNotFound($cityName, self::COUNTRY_NAME);
                    $this->createImportInfoDetails("420", self::PROVIDER_ID);
                }
            }
        }
        $this->deleteMissingProviders(self::PROVIDER_ID, $existingCityProviders);
    }
}
