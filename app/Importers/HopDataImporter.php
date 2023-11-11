<?php

declare(strict_types=1);

namespace App\Importers;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class HopDataImporter extends DataImporter
{

    protected Crawler $sections;

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://hop.bike/en/location-list.html");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());
            $this->stopExecution = true;
        }

        $crawler = new Crawler($html);

        $this->sections = $crawler->filter('section[class="location-list"]');

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

        $countryList = $this->sections->filter('div[class="location-list-body-main-title"]');
        $citySectionList = $this->sections->filter('div[class="location-list-body-main-list"]');

        foreach ($countryList as $index => $country) {
            $citySection = $citySectionList->eq($index);
            $cities = $citySection->filter('li[class="location-list-body-main-list-title text-body-xl"]');

            foreach ($cities as $city) {
                $locations[] = [
                    'country' => $country->textContent,
                    'city' => $city->textContent
                ];

            }
        }
        foreach ($locations as $location) {
            $location['country'] = preg_replace('/\s+/', '', $location['country']);
            $location['city'] = preg_replace('/\s+/', '', $location['city']);

            $provider = $this->load($location['city'], $location['country']);

            if ($provider !== "") {
                $existingCityProviders[] = $provider;
            }
        }

        $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
    }
}
