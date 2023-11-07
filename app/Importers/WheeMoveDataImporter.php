<?php

declare(strict_types=1);

namespace App\Importers;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class WheeMoveDataImporter extends DataImporter
{
    protected Crawler $sections;

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://www.wheemove.com/");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());
            $this->stopExecution = true;
        }

        $crawler = new Crawler($html);

        $this->sections = $crawler->filter('section[data-id="4b8f82b"], section[data-id="59def84"]');

        if (count($this->sections) === 0) {
            $this->createImportInfoDetails("204", self::getProviderName());

            $this->stopExecution = true;
        }

        return $this;
    }

    public function transform(): void
    {
        $existingCityProviders = [];
        $this->extract();

        if ($this->stopExecution) {
            return;
        }
        $cityNames = [];

        $names_temp = $this->sections->first()->filter('span[class="elementor-icon-list-text"]');

        foreach ($names_temp as $name) {
            $cityNames[] = $name->nodeValue;
        }
        $names_temp = $this->sections->last()->filter('span[class="elementor-icon-list-text"]');

        foreach ($names_temp as $name) {
            $cityNames[] = $name->nodeValue;
        }

        foreach ($cityNames as $name) {
            $provider = $this->load($name, "Spain");

            if ($provider !== "") {
                $existingCityProviders[] = $provider;
            }
        }

        $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
    }
}
