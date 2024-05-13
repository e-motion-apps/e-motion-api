<?php

declare(strict_types=1);

namespace App\Importers;

use App\Enums\ServicesEnum;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class BerylDataImporter extends DataImporter
{
    private const COUNTRY_NAME = "United Kingdom";

    protected Crawler $sections;

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://beryl.cc/where-you-can-hire");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());

            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);
        $this->sections = $crawler->filter('div[class="view-content col-xs-12 col-sm-12"]')->filter("div[class='views-row']");

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
            $crawler = new Crawler($section);
            $cityName = $crawler->filter("h3")->text();
            $this->data[] = $cityName;

            $servicesNodes = $crawler->filter('div[class="field--label"]');
            $availableServices = [];

            foreach ($servicesNodes as $serviceNode) {
                $serviceCrawler = new Crawler($serviceNode);
                $service = $serviceCrawler->text();

                if ($service === "e-Bikes") {
                    $availableServices[] = ServicesEnum::Bike;
                }

                if ($service === "e-Scooters") {
                    $availableServices[] = ServicesEnum::Escooter;
                }

                if ($service === "Cargo") {
                    $availableServices[] = ServicesEnum::Cargo;
                }
            }

            if ($cityName === "Bournemouth Christchurch Poole") {
                $arrayOfCityNames = explode(" ", $cityName);

                foreach ($arrayOfCityNames as $cityName) {
                    $provider = $this->load($cityName, self::COUNTRY_NAME, $lat = "", $long = "", $availableServices);

                    if ($provider !== "") {
                        $existingCityProviders[] = $provider;
                    }
                }
            } elseif ($cityName === "West Midlands") {
                $cityName = "Birmingham";
            } elseif ($cityName === "Isle of Wight") {
                $arrayOfCityNames = ["Cowes", "East Cowes", "Newport", "Ryde", "Sandown", "Shanklin"];

                foreach ($arrayOfCityNames as $cityName) {
                    $provider = $this->load($cityName, self::COUNTRY_NAME, $lat = "", $long = "", $availableServices);

                    if ($provider !== "") {
                        $existingCityProviders[] = $provider;
                    }
                }
            }
            $provider = $this->load($cityName, self::COUNTRY_NAME, $lat = "", $long = "", $availableServices);

            if ($provider !== "") {
                $existingCityProviders[] = $provider;
            }
        }

        $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
    }

    public function test()
    {
        $this->extract()->transform();
        $sectionCount = count($this->sections);
        $this->data[] = ["sections" => "$sectionCount"];

        return $this->data;
    }
}
