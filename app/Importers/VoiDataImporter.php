<?php

declare(strict_types=1);

namespace App\Importers;

use App\Models\City;
use App\Models\CityAlternativeName;
use App\Models\Country;
use App\Services\MapboxGeocodingService;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class VoiDataImporter extends DataImporter
{
    private const PROVIDER_ID = 13;

    protected Crawler $sections;
    private string $countryName;

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://www.voi.com/locations");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::PROVIDER_ID);

            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);
        $this->sections = $crawler->filter("section.locations-list .holder > div > .s-col-6.col-4.mb-4");

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
            $country = null;

            foreach ($section->childNodes as $node) {
                if ($node->nodeName === "h4") {
                    $this->countryName = trim($node->nodeValue);
                }

                if ($node->nodeName === "ul") {
                    foreach ($node->childNodes as $city) {
                        if ($city->nodeName === "li") {
                            $cityName = trim($city->nodeValue);

                            $city = City::query()->where("name", $cityName)->first();
                            $alternativeCityName = CityAlternativeName::query()->where("name", $cityName)->first();

                            if ($city || $alternativeCityName) {
                                $cityId = $city ? $city->id : $alternativeCityName->city_id;

                                $this->createProvider($cityId, self::PROVIDER_ID);
                                $existingCityProviders[] = $cityId;
                            } else {
                                $country = Country::query()->where("name", $this->countryName)->orWhere("alternative_name", $this->countryName)->first();

                                if ($country) {
                                    $coordinates = $mapboxService->getCoordinatesFromApi($cityName, $this->countryName);
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
                                    $this->countryNotFound($cityName, $this->countryName);
                                    $this->createImportInfoDetails("420", self::PROVIDER_ID);
                                }
                            }
                        }
                    }
                }
            }
        }
        $this->deleteMissingProviders(self::PROVIDER_ID, $existingCityProviders);
    }
}
