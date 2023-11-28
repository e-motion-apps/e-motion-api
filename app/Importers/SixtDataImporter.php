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
            $headers = [
                "User-Agent" => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
            ];
            $response = $this->client->get("https://www.sixt.com/share/e-scooter/#/", ["headers" => $headers]);
            $html = $response->getBody()->getContents();
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
        $countryName = "";

        foreach ($this->sections as $section) {
            $node = $section->parentNode->parentNode->parentNode;

            foreach ($node->childNodes as $country) {
                if ($country instanceof \DOMElement) {
                    $class = $country->getAttribute("class");

                    if ($class === "title") {
                        $countryName = trim(preg_replace("/[^a-zA-Z ]/", "", $country->nodeValue));
                    }
                }
            }

            foreach ($section->childNodes as $city) {
                if ($city->nodeName === "li") {
                    $cityName = trim(preg_replace('/\s+/', "", $city->nodeValue));
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
