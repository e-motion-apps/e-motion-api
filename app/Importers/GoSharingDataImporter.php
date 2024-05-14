<?php

declare(strict_types=1);

namespace App\Importers;

use App\Enums\ServicesEnum;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class GoSharingDataImporter extends DataImporter
{
    protected Crawler $sections;

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://nl.go-sharing.com/en/locations/");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());

            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);
        $this->sections = $crawler->filter('section[id="show-cities"]');

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
        $countryNames = [];

        foreach ($this->sections as $section) {
            $sectionCrawler = new Crawler($section);
            $countries = $sectionCrawler->filter("h3");

            foreach ($countries as $country) {
                $countryNames[] = $country->textContent;
            }

            $countryCitiesRows = $sectionCrawler->filter('div[class="row location-row"]');
            $countryCitiesArray = [];

            foreach ($countryCitiesRows as $countryCities) {
                $locationsCrawler = new Crawler($countryCities);
                $locations = $locationsCrawler->filter('div[class="location"]');

                $citiesArray = [];

                foreach ($locations as $location) {
                    $cityCrawler = new Crawler($location);
                    $cityName = $cityCrawler->text();

                    $activeServicesArray = [];
                    $activeServices = $cityCrawler->filter('div[class="location__icons__icon active"]');

                    foreach ($activeServices as $activeService) {
                        $serviceCrawler = new Crawler($activeService);
                        $serviceType = $serviceCrawler->filter("img")->attr("alt");

                        if ($serviceType === "E-Scooter") {
                            $activeServicesArray[] = ServicesEnum::Escooter;
                        }

                        if ($serviceType === "E-Bike") {
                            $activeServicesArray[] = ServicesEnum::Bike;
                        }
                    }

                    $citiesArray[] = [
                        "cityName" => $cityName,
                        "services" => $activeServicesArray,
                    ];
                }
                $countryCitiesArray[] = $citiesArray;
            }

            $countryCount = count($countryNames);

            for ($i = 0; $i < $countryCount; $i++) {
                $countryName = $countryNames[$i];
                $cities = $countryCitiesArray[$i];

                foreach ($cities as $city) {
                    $cityName = $city["cityName"];
                    $services = $city["services"];

                    $provider = $this->load($cityName, $countryName, $lat = "", $long = "", $services);

                    if ($provider !== "") {
                        $existingCityProviders[] = $provider;
                    }
                }
            }

            $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
        }
    }
}
