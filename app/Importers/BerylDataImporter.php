<?php

declare(strict_types=1);

namespace App\Importers;

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
        $this->sections = $crawler->filter("div > section > div .inner");

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
            foreach ($section->childNodes as $node) {
                if ($node->nodeName === "div") {
                    foreach ($node->childNodes as $city) {
                        if ($city->nodeName === "h3") {
                            $cityName = $city->nodeValue;
                        }

                        $eScootersFound = false;

                        if ($city->nodeValue === "e-Scooters") {
                            $eScootersFound = true;
                        }

                        if ($eScootersFound) {
                            $cityName = str_replace(["E-scooters", "and ", ","], "", $cityName);
                            $cityName = trim($cityName);

                            if ($cityName === "Bournemouth Christchurch Poole") {
                                $arrayOfCityNames = explode(" ", $cityName);

                                foreach ($arrayOfCityNames as $cityName) {
                                    $provider = $this->load($cityName, self::COUNTRY_NAME);

                                    if ($provider !== "") {
                                        $existingCityProviders[] = $provider;
                                    }
                                }
                            } else {
                                if ($cityName === "West Midlands") {
                                    $cityName = "Birmingham";
                                }

                                if ($cityName === "Isle of Wight") {
                                    $arrayOfCityNames = ["Cowes", "East Cowes", "Newport", "Ryde", "Sandown", "Shanklin"];

                                    foreach ($arrayOfCityNames as $cityName) {
                                        $provider = $this->load($cityName, self::COUNTRY_NAME);

                                        if ($provider !== "") {
                                            $existingCityProviders[] = $provider;
                                        }
                                    }
                                }
                                $provider = $this->load($cityName, self::COUNTRY_NAME);

                                if ($provider !== "") {
                                    $existingCityProviders[] = $provider;
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
