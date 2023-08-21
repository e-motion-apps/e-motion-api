<?php

declare(strict_types=1);

namespace App\Importers;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class ZwingsDataImporter extends DataImporter
{
    private const COUNTRY_NAME = "United Kingdom";

    protected Crawler $sections;

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://www.zwings.co.uk/locations/");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());

            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);
        $this->sections = $crawler->filter("main > section > div .background-image");

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
                if ($node->nodeName === "div") {
                    foreach ($node->childNodes as $city) {
                        if ($city->nodeName === "p") {
                            $cityName = $city->nodeValue;

                            $provider = $this->load($cityName, self::COUNTRY_NAME);

                            if ($provider !== "") {
                                $existingCityProviders[] = $provider;
                            }
                        }
                    }
                }
            }
        }
        $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
    }
}
