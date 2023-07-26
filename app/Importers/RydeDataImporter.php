<?php

declare(strict_types=1);

namespace App\Importers;

use App\Models\City;
use App\Models\CityAlternativeName;
use App\Models\Country;
use App\Services\MapboxGeocodingService;
use Symfony\Component\DomCrawler\Crawler;
use Throwable;

class RydeDataImporter extends DataImporter
{
    private const PROVIDER_ID = 20;

    protected Crawler $sections;

    private string $countryName;

    public function extract(): static
    {
        try {
            $html = file_get_contents("https://www.ryde-technology.com/Locations");
        } catch (Throwable) {
            $this->createImportInfoDetails("400", self::PROVIDER_ID);

            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);
        $this->sections = $crawler->filter('.section.neutral-50.wf-section');

        if (count($this->sections) === 0) {
            $this->createImportInfoDetails("204", self::PROVIDER_ID);
            dump(1234);
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
                if ($node->nodeName === "heading-h2") {
                    $flagEmojiPattern = '/[\x{1F1E6}-\x{1F1FF}]{2}/u';
                    $this->countryName = preg_replace($flagEmojiPattern, '', $this->countryName);
                    $this->countryName = trim($node->nodeValue);
                }

                if ($node->nodeName === "location") {
                }
            }
        }
    }
}
