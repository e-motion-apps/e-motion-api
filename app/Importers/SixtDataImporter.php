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
//            $html = file_get_contents("https://www.sixt.com/share/e-scooter/#/");
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());

            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);

        $this->sections = $crawler->filter("div.item div.middle div.content ul");

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
        $countryName = '';

        foreach ($this->sections as $section) {
            $node = $section->parentNode->parentNode->parentNode;

            foreach ($node->childNodes as $country) {
                if ($country instanceof \DOMElement) {
                    $class = $country->getAttribute('class');
                    if ($class === "title")
                        $countryName = trim(preg_replace('/[^a-zA-Z ]/', '', $country->nodeValue));

                }
            }
            foreach ($section->childNodes as $city) {
                if ($city->nodeName == "li") {
                    $cityName = trim(preg_replace('/\s+/', '', ($city->nodeValue)));
                    $provider = $this->load($cityName, $countryName);

                    if ($provider !== "") {
                        $existingCityProviders[] = $provider;
                    }
                }
            }

        }
        $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
    }
}
