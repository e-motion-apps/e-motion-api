<?php

declare(strict_types=1);

namespace App\Importers;

use App\Models\City;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class BeamDataImporter extends DataImporter
{
    private string $countryName;

    private bool $hasEscooters = false;

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
            $country = null;
            foreach ($section->childNodes as $node) {
                if ($node->nodeName === "h4") {
                    $this->countryName = $node->nodeValue;
                }
                if ($node->nodeName === "div") {
                    foreach ($node->childNodes as $div) {
                        if ($div->nodeName === "div") {
                            foreach ($div->childNodes as $city) {
                                if ($city->nodeName === "img" && $city->getAttribute("src") === "https://uploads-ssl.webflow.com/63c4acbedbab5dea8b1b98cd/63d8a5b60da91e7d71298637_map-vehicle-saturn.png") {
                                    $this->hasEscooters = true;
                                } elseif ($city->nodeName === "img" && $city->getAttribute("src") !== "https://uploads-ssl.webflow.com/63c4acbedbab5dea8b1b98cd/63d8a5b60da91e7d71298637_map-vehicle-saturn.png") {
                                    $this->hasEscooters = false;
                                }
                                if ($city->nodeName === "p" && $this->hasEscooters == true) {
                                    $search = ["\u{00A0}", "\u{200D}"];
                                    $cityName = str_replace($search, '', $city->nodeValue);
                                    $cityName = str_replace("Prefecture", '', $city->nodeValue);
                                    $cityName = preg_replace('/[\p{Hiragana}\p{Katakana}\p{Han}]+/u', '', $cityName);
                                    $arrayOfCitiesNames = explode("  ", $cityName);
                                    $arrayOfCitiesNames = array_filter($arrayOfCitiesNames, function ($record) {
                                        return strlen($record) > 1;
                                    });
                                    $arrayOfCitiesNames  = array_filter($arrayOfCitiesNames, function ($record) {
                                        return strpos($record, "•") === false;
                                    });
                                    foreach ($arrayOfCitiesNames as $cityName) {
                                        $cityName = trim($cityName);
                                        $city = City::query()->where("name", $cityName)->first();
                                    }
                                    $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
