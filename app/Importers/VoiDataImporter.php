<?php

declare(strict_types=1);

namespace App\Importers;

use App\Services\MapboxGeocodingService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class VoiDataImporter extends DataImporter
{
    protected Crawler $sections;
    private string $countryName;

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
            $response = $this->client->get("https://www.voi.com/locations");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());

            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);
        $this->sections = $crawler->filter("section.locations-list .holder > div > .s-col-6.col-4.mb-4");

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
                if ($node->nodeName === "h4") {
                    $this->countryName = trim($node->nodeValue);
                }

                if ($node->nodeName === "ul") {
                    foreach ($node->childNodes as $city) {
                        if ($city->nodeName === "li") {
                            $cityName = trim($city->nodeValue);

                            $provider = $this->load($cityName, $this->countryName);

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
