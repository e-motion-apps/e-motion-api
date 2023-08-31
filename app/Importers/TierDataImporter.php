<?php

declare(strict_types=1);

namespace App\Importers;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class TierDataImporter extends DataImporter
{
    protected Crawler $sections;
    protected string $countryName;

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://www.tier.app/en/where-to-find-us");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());

            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);
        $this->sections = $crawler->filter("main div.relative section.Accordion__AccordionWrapper-sc-ehu24-0.fvluSp>li > div.items-center");

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
                if ($node->nodeName === "h5") {
                    $this->countryName = trim($node->nodeValue);

                    continue 2;
                }

                foreach ($node->childNodes as $childNode) {
                    if (trim($childNode->nodeValue) !== "") {
                        $cityName = trim($childNode->nodeValue);
                        $provider = $this->load($cityName, $this->countryName);

                        if ($provider !== "") {
                            $existingCityProviders[] = $provider;
                        }
                    }
                }
            }
        }

        $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
    }
}
