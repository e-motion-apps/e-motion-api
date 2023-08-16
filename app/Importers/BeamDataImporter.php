<?php

declare(strict_types=1);

namespace App\Importers;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class BeamDataImporter extends DataImporter
{
    protected Crawler $sections;

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://partner.ridebeam.com/cities");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());

            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);
        $this->sections = $crawler->filter("div .find-beam-box");

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
                if ($node->nodeName === "h4") {
                    $countryName = $node->nodeValue;
                }

                if ($node->nodeName === "div") {
                    foreach ($node->childNodes as $div) {
                        if ($div->nodeName === "div") {
                            foreach ($div->childNodes as $city) {
                                if ($city->nodeName === "img" && $city->getAttribute("src") === "https://uploads-ssl.webflow.com/63c4acbedbab5dea8b1b98cd/63d8a5b60da91e7d71298637_map-vehicle-saturn.png") {
                                    $hasEscooters = true;
                                } elseif ($city->nodeName === "img" && $city->getAttribute("src") !== "https://uploads-ssl.webflow.com/63c4acbedbab5dea8b1b98cd/63d8a5b60da91e7d71298637_map-vehicle-saturn.png") {
                                    $hasEscooters = false;
                                }

                                if ($city->nodeName === "p" && $hasEscooters === true) {
                                    $search = ["\u{00A0}", "\u{200D}"];
                                    $valueToDelete = "Selangor";
                                    $cityName = str_replace("Prefecture", "", $city->nodeValue);
                                    $cityName = preg_replace('/[\p{Hiragana}\p{Katakana}\p{Han}]+/u', "", $cityName);
                                    $cityName = str_replace($search, "", $cityName);
                                    $cityName = preg_replace('/(?<=[^\s_\-])(?=[A-Z])/', "  ", $city->nodeValue);
                                    $arrayOfCitiesNames = explode("  ", $cityName);
                                    $arrayOfCitiesNames = array_filter($arrayOfCitiesNames, fn($value) => $value !== $valueToDelete);
                                    $arrayOfCitiesNames = array_filter($arrayOfCitiesNames, fn($record) => strlen($record) > 1);
                                    $arrayOfCitiesNames = array_filter($arrayOfCitiesNames, fn($record) => strpos($record, "•") === false);

                                    foreach ($arrayOfCitiesNames as $cityName) {
                                        if ($cityName === "Selangor") {
                                        } else {
                                            $cityName = trim($cityName);

                                            if ($countryName === "Korea") {
                                                $countryName = "South Korea";
                                            }
                                            $provider = $this->load($cityName, $countryName);
                                        }

                                        if ($provider !== "") {
                                            $existingCityProviders[] = $provider;
                                        }
                                    }
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
