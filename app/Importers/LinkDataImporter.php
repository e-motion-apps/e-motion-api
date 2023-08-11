<?php

declare(strict_types=1);

namespace App\Importers;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class LinkDataImporter extends DataImporter
{
    protected Crawler $sections;
    private string $countryName = "";

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://superpedestrian.com/locations");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());

            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);
        $this->sections = $crawler->filter(".Main-content .sqs-row.row > .col p > strong");

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
                $countryName = trim($node->nodeValue);

                foreach ($node->parentNode->parentNode->parentNode->childNodes as $i => $cityName) {
                    if ($i === 0 || !trim($cityName->nodeValue)) {
                        continue;
                    }

                    $name = $cityName->nodeValue;

                    $cities = [];

                    if (str_contains($name, "(") && str_contains($name, ")")) {
                        $names = explode("(", $name)[1];
                        $names = explode(")", $names)[0];
                        $names = explode(", ", $names);

                        foreach ($names as $name) {
                            $cities[] = str_replace("*", "", $name);
                        }
                    } else {
                        $cities[] = $name;
                    }

                    foreach ($cities as $name) {
                        $provider = $this->load($name, $countryName);

                        if ($provider !== "") {
                            $existingCityProviders[] = $provider;
                        }
                    }
                }
            }
        }

        $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
    }
}
