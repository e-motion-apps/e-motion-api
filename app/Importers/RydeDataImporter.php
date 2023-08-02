<?php

declare(strict_types=1);

namespace App\Importers;

use App\Models\City;
use App\Models\CityAlternativeName;
use App\Models\Country;
use App\Services\MapboxGeocodingService;
use Symfony\Component\DomCrawler\Crawler;
use Throwable;

class RydeDataImporter extends DataImporter
{
    protected Crawler $sections;
    private string $countryName;

    public function extract(): static
    {
        try {
            $html = file_get_contents("https://www.ryde-technology.com/Locations");
        } catch (Throwable) {
            $this->createImportInfoDetails("400", self::getProviderName());

            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);
        $this->sections = $crawler->filter("div.neutral-50 .container-1144");

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

        $mapboxService = new MapboxGeocodingService();
        $existingCityProviders = [];

        foreach ($this->sections as $section) {
            $country = null;

            foreach ($section->childNodes as $node) {
                if ($node->nodeName === "h2") {
                    $flagEmojiPattern = '/[\x{1F1E6}-\x{1F1FF}]{2}/u';
                    $this->countryName = trim($node->nodeValue);
                    $this->countryName = preg_replace($flagEmojiPattern, "", $this->countryName);
                    $this->countryName = trim($this->countryName);
                }

                if ($node->nodeName === "div") {
                    foreach ($node->childNodes as $div) {
                        if ($div->nodeName === "div") {
                            foreach ($div->childNodes as $city)
                            if ($city->nodeName === "h1") {
                                $cityName = trim($city->nodeValue);
                                $city = City::query()->where("name", $cityName)->first();
                                $alternativeCityName = CityAlternativeName::query()->where("name", $cityName)->first();

                                if ($city || $alternativeCityName) {
                                    $cityId = $city ? $city->id : $alternativeCityName->city_id;

                                    $this->createProvider($cityId, self::getProviderName());
                                    $existingCityProviders[] = $cityId;
                                } else {
                                    $country = Country::query()->where("name", $this->countryName)->orWhere("alternativeName", $this->countryName)->first();

                                    if ($country) {
                                        $coordinates = $mapboxService->getCoordinatesFromApi($cityName, $this->countryName);
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
                                        $this->countryNotFound($cityName, $this->countryName);
                                        $this->createImportInfoDetails("420", self::getProviderName());
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
        }
    }
}
