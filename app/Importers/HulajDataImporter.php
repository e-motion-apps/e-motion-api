<?php

declare(strict_types=1);

namespace App\Importers;

use App\Services\MapboxGeocodingService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class HulajDataImporter extends DataImporter
{
    private const COUNTRY_NAME = "Poland";

    protected Crawler $sections;

    public function __construct(
        Client $client,
        protected MapboxGeocodingService $mapboxService,
    )
    {
        parent::__construct($client);
    }

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://hulaj.eu/miasta/");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());
            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);
        $this->sections = $crawler->filter(".wp-block-media-text > .wp-block-media-text__content > h2");

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

            if (str_contains($cityName, "przerwa")) {
                continue;
            }
            $provider = $this->load($cityName, self::COUNTRY_NAME);

            if ($provider !== "") {
                $existingCityProviders[] = $provider;
            }
        }
        $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
    }
}
