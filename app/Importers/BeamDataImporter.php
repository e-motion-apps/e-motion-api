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
        $this->sections = $crawler->filter("div.find-beam-box");

        if (count($this->sections) === 0) {
            $this->createImportInfoDetails("204", self::getProviderName());
            $this->stopExecution = true;
        }

        return $this;
    }

    public function transform(): void
    {
        $escooterImageUrl = "https://assets-global.website-files.com/63c4acbedbab5dea8b1b98cd/63d8a5b60da91e7d71298637_map-vehicle-saturn.png";
        $bikeImageUrl = "https://assets-global.website-files.com/63c4acbedbab5dea8b1b98cd/63d8a5b7f06e4a42de02241b_map-vehicle-saturn-apollo.png";

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
                                if (!$city->nodeName) {
                                    continue;
                                }

                                $hasEscooters = false;
                                $hasBikes = false;

                                if ($city->getAttribute("src") === $escooterImageUrl) {
                                    $hasEscooters = true;
                                }

                                if ($city->getAttribute("src") === $bikeImageUrl) {
                                    $hasBikes = true;
                                }

                                if ($city->nodeName === "p") {
                                    $search = ["\u{00A0}", "\u{200D}", "Prefecture"];
                                    $valueToDelete = "Selangor";
                                    $cityName = preg_replace('/[\p{Hiragana}\p{Katakana}\p{Han}]+/u', "", $city->nodeValue);
                                    $cityName = str_replace($search, "", $cityName);
                                    $cityName = preg_replace('/(?<=[^\s_\-])(?=[A-Z])/', "  ", $cityName);
                                    $arrayOfCitiesNames = explode("  ", $cityName);
                                    $arrayOfCitiesNames = array_filter($arrayOfCitiesNames, fn($value) => $value !== $valueToDelete);
                                    $arrayOfCitiesNames = array_filter($arrayOfCitiesNames, fn($record) => strlen($record) > 1);
                                    $arrayOfCitiesNames = array_filter($arrayOfCitiesNames, fn($record) => !str_contains($record, "•"));

                                    foreach ($arrayOfCitiesNames as $cityName) {
                                        if ($cityName !== "Selangor") {
                                            $cityName = trim($cityName);

                                            if ($countryName === "Korea") {
                                                $countryName = "South Korea";
                                            }
                                            $services = [];

                                            if ($hasBikes) {
                                                $services[] = "bike";
                                            }

                                            if ($hasEscooters) {
                                                $services[] = "escooter";
                                            }
                                            $services = ["escooter", "bike"];
                                            $provider = $this->load($cityName, $countryName, $lat = "", $long = "", $services);

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
        }
        $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
    }
}
