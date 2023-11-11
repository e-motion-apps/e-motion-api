<?php

declare(strict_types=1);

namespace App\Importers;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class SixtDataImporter extends DataImporter
{
    protected Crawler $sections;

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://www.sixt.com/share/e-scooter/#/");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());

            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);

        $this->sections = $crawler->filter("section div.content div div div.item div div.item div.middle div.content div ul");

        if ($this->sections->count() === 0) {
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
            $node = $section->parentNode->parentNode->parentNode;

            foreach ($node->childNodes as $country) {
                if ($country instanceof \DOMElement) {
                    $class = $country->getAttribute('class');
                    if($class==="title")
                        $countryName = $country->nodeValue;
        }
            }
            foreach($section->childNodes as $city)
                if($city->nodeName == "li")
                    $cityName = $city->nodeValue;

            $provider = $this->load($cityName, $countryName);

            if ($provider !== "") {
                $existingCityProviders[] = $provider;
            }
        }
        $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
    }
}
