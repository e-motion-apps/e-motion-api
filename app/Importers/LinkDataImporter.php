<?php

declare(strict_types=1);

namespace App\Importers;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class LinkDataImporter extends DataImporter
{
    protected Crawler $sections;

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://superpedestrian.com/locations");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());

            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);
        $this->sections = $crawler->filter(".Main-content .sqs-row.row > .col p");

        if (count($this->sections) === 0) {
            $this->createImportInfoDetails("204", self::getProviderName());

            $this->stopExecution = true;
        }

        return $this;
    }

    public function transform(): void
    {
        $countryName = "";

        $cityName = "";

        $states = [
            "Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware", "Florida",
            "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine",
            "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska",
            "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Carolina", "North Dakota",
            "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Rhode Island", "South Carolina", "South Dakota", "Tennessee",
            "Texas", "Utah", "Vermont", "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming",
        ];

        if ($this->stopExecution) {
            return;
        }

        $existingCityProviders = [];

        $skipFirstIteration = true;

        foreach ($this->sections as $section) {
            if ($skipFirstIteration) {
                $skipFirstIteration = false;

                continue;
            }

            foreach ($section->childNodes as $node) {
                if ($node->nodeName === "strong") {
                    if (!in_array($node->nodeValue, $states, true)) {
                        $countryName = trim($node->nodeValue);
                    } else {
                        $countryName = "United States";
                    }
                }

                if ($node->nodeName === "#text") {
                    $cityName = $node->nodeValue;
                } else if ($node->nodeName === "a") {
                    $cityName = $node->nodeValue;
                }

                if ($cityName === " ") {
                    continue;
                }
                $provider = $this->load($countryName, $cityName);

                if ($provider !== "") {
                    $existingCityProviders[] = $provider;
                }
            }
        }
        $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
    }
}
