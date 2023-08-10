<?php

declare(strict_types=1);

namespace App\Importers;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class BitMobilityDataImporter extends DataImporter
{
    private const COUNTRY_NAME = "Italy";

    protected Crawler $sections;
    protected string $html;

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://bitmobility.it/dove-siamo/");
            $this->html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());
            $this->stopExecution = true;

            return $this;
        }

        return $this;
    }

    public function transform(): void
    {
        if ($this->stopExecution) {
            return;
        }
        $existingCityProviders = [];
        $crawler = new Crawler($this->html);
        $this->sections = $crawler->filter(".wpb_content_element > .wpb_wrapper > p > a");

        if (count($this->sections) === 0) {
            $this->createImportInfoDetails("204", self::getProviderName());

            $this->stopExecution = true;
        }

        foreach ($this->sections as $section) {
            $cityName = ucwords(strtolower($section->nodeValue));
            $provider = $this->load($cityName, self::COUNTRY_NAME);

            if ($provider !== "") {
                $existingCityProviders[] = $provider;
            }
        }
        $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
    }
}
