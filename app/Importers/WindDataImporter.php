<?php

declare(strict_types=1);

namespace App\Importers;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class WindDataImporter extends DataImporter
{
    private const COUNTRY_NAME = "United Kingdom";

    protected Crawler $sections;

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://wind.yango.com/official-website/#/rider?lang=global");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());

            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);
        $this->sections = $crawler->filter("body");

        if (count($this->sections) === 0) {
            dump(123);
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
                    dump($node->nodeValue);
                    foreach ($node->childNodes as $city) {
                        dump($city->nodeName);
                    }
                }
            }
            $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
        }
    }
}
