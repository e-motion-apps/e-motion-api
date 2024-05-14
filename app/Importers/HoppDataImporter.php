<?php

declare(strict_types=1);

namespace App\Importers;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class HoppDataImporter extends DataImporter
{
    protected Crawler $sections;

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://hopp.bike/locations");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());
            $this->stopExecution = true;
        }

        $crawler = new Crawler($html);

        $this->sections = $crawler->filter('section[class="sc-3ad02d05-0 eZsxrq"]');

        if (count($this->sections) === 0) {
            $this->createImportInfoDetails("204", self::getProviderName());

            $this->stopExecution = true;
        }

        return $this;
    }

    public function transform(): void
    {
        $existingCityProviders = [];

        if ($this->stopExecution) {
            return;
        }

        $locations = [];

        $countryList = $this->sections->filter('h1[class="sc-188713c8-0 kHXWNs"]');
        $citySectionList = $this->sections->filter('div[class="sc-3ad02d05-0 gqLRPj"]');

        foreach ($countryList as $index => $country) {
            $citySection = $citySectionList->eq($index);
            $cities = $citySection->filter('p[class="sc-188713c8-0 bGGtqk"]');

            foreach ($cities as $city) {
                $locations[] = [
                    "country" => $country->textContent,
                    "city" => $city->textContent,
                ];
            }
        }

        foreach ($locations as $location) {
            $location["country"] = preg_replace('/\s+/', "", $location["country"]);
            $location["city"] = preg_replace('/\s+/', "", $location["city"]);

            $provider = $this->load($location["country"], $location["city"]);

            if ($provider !== "") {
                $existingCityProviders[] = $provider;
            }
        }
        $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
    }
}
