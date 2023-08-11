<?php

declare(strict_types=1);

namespace App\Importers;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class BerylDataImporter extends DataImporter
{
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
            $country = null;

            foreach ($section->childNodes as $node) {
                if ($node->nodeName === "div") {
                    foreach ($node->childNodes as $city) {
                        if ($city->nodeValue === "e-Scooters") {
                            $eScootersFound = true;
                        } else {
                            $eScootersFound = false;
                        }
                        dump($eScootersFound);
                        if ($city->nodeName === "h3" && $eScootersFound) {
                            dump(456);
                        }
                    }
                }
            }
        }
        $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
    }
}
