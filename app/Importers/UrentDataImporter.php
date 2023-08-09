<?php

declare(strict_types=1);

namespace App\Importers;

use App\Services\MapboxGeocodingService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Symfony\Component\DomCrawler\Crawler;

class UrentDataImporter extends DataImporter
{
    private const COUNTRY_NAME = "Russia";

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
            $response = $this->client->get("https://start.urent.ru/");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());

            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);
        $this->sections = $crawler->filter(".city-block-1 > div > div > .grid-item-1 > ul > li");

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

            if (preg_match('/\p{Cyrillic}/u', $cityName) === 1) {
                $tr = new GoogleTranslate("en");
                $cityName = $tr->translate($cityName);
            }
            $cityName = str_replace("-", " ", $cityName);

            $provider = $this->load($cityName, self::COUNTRY_NAME);

            if ($provider !== "") {
                $existingCityProviders[] = $provider;
            }
        }
        $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
    }
}
