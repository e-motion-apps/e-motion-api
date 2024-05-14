<?php

declare(strict_types=1);

namespace App\Importers;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class QuickDataImporter extends DataImporter
{
    private const COUNTRY_NAME = "Poland";

    protected Crawler $sections;

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://quick-app.eu/lokalizacje/");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());
            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);
        $this->sections = $crawler->filter(".tx-hd-desc > ul > li");

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

            $provider = $this->load(self::COUNTRY_NAME, $cityName);

            if ($provider !== "") {
                $existingCityProviders[] = $provider;
            }
        }
        $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
    }
}
