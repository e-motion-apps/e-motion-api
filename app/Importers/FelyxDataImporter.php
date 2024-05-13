<?php

declare(strict_types=1);

namespace App\Importers;

use App\Enums\ServicesEnum;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class FelyxDataImporter extends DataImporter
{
    protected Crawler $sections;
    private array $services = [ServicesEnum::Emoped];

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://felyx.com/products/our-locations/");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());
            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);
        $this->sections = $crawler->filter("p.styles_cityName__XdcTJ");

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
            $countryNode = $section->parentNode->parentNode->parentNode;

            foreach ($countryNode->childNodes as $country) {
                if ($country->nodeName === "h3") {
                    $countryName = trim($country->nodeValue);

                    $provider = $this->load($cityName, $countryName, $lat = "", $long = "", $this->services);

                    if ($provider !== "") {
                        $existingCityProviders[] = $provider;
                    }
                }
            }
        }
        $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
    }
}
