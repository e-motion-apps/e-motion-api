<?php

declare(strict_types=1);

namespace App\Importers;

use App\Models\City;
use App\Models\CityAlternativeName;
use App\Models\Country;
use App\Services\MapboxGeocodingService;
use Symfony\Component\DomCrawler\Crawler;
use Throwable;

class BeamDataImporter extends DataImporter
{
    private const PROVIDER_ID = 17;

    protected Crawler $sections;
    private string $countryName;

    public function extract(): static
    {
        try {
            $html = file_get_contents("https://partner.ridebeam.com/cities");
        } catch (Throwable) {
            $this->createImportInfoDetails("400", self::PROVIDER_ID);

            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);
        $this->sections = $crawler->filter("div .beam-col-main-box");

        if (count($this->sections) === 0) {
            $this->createImportInfoDetails("204", self::PROVIDER_ID);
            $this->stopExecution = true;
        }

        return $this;
    }

    public function transform(): void
    {
        if ($this->stopExecution) {
            return;
        }

        $mapboxService = new MapboxGeocodingService();
        $existingCityProviders = [];

        foreach ($this->sections as $section) {
            $country = null;

            foreach ($section->childNodes as $node) {
                if ($node->nodeName === "div") {
                    foreach ($node->childNodes as $city) {
                        if ($city->nodeName === "p") {
                            $cityName = trim($city->nodeValue);
                            dump($cityName);
                        }
                    }
                }
            }
        }
    }
}
