<?php

declare(strict_types=1);

namespace App\Importers;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class RydeDataImporter extends DataImporter
{
    protected Crawler $sections;
    private string $countryName;

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://www.ryde-technology.com/Locations");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());

            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);
        $this->sections = $crawler->filter("div.neutral-50 .container-1144");

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
                if ($node->nodeName === "h2") {
                    $flagEmojiPattern = '/[\x{1F1E6}-\x{1F1FF}]{2}/u';
                    $this->countryName = trim($node->nodeValue);
                    $this->countryName = preg_replace($flagEmojiPattern, "", $this->countryName);
                    $this->countryName = trim($this->countryName);
                }

                if ($node->nodeName === "div") {
                    foreach ($node->childNodes as $div) {
                        if ($div->nodeName === "div") {
                            foreach ($div->childNodes as $city)
                            if ($city->nodeName === "h1") {
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
}
