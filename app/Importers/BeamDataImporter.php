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


        $this->sections = $crawler->filter("div .find-beam-box");


        $crawler->filter('br')->each(function (Crawler $brNode) {

            $brDOMNode = $brNode->getNode(0);

            $spaceTextNode = $brDOMNode->ownerDocument->createTextNode('  ');

            $brDOMNode->parentNode->replaceChild($spaceTextNode, $brDOMNode);
        });

        $modifiedHtml = $crawler->html();


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
                    foreach ($node->childNodes as $div) {
                        if ($div->nodeName === "div") {
                            foreach ($div->childNodes as $city) {
                                if ($city->nodeName === "p") {
                                    $search = ["\u{00A0}", "\u{200D}"];
                                    $cityName = str_replace($search, '', $city->nodeValue);
                                    $cityName = preg_replace('/[\p{Hiragana}\p{Katakana}\p{Han}]+/u', '', $cityName);
                                    $arrayOfCitiesNames = explode("  ", $cityName);
                                    $arrayOfCitiesNames = array_filter($arrayOfCitiesNames, function ($record) {
                                        return strlen($record) > 1;
                                    });
                                    $arrayOfCitiesNames  = array_filter($arrayOfCitiesNames, function ($record) {
                                        return strpos($record, "•") === false;
                                    });
                                    foreach ($arrayOfCitiesNames as $cityName) {
                                        $cityName = trim($cityName);
                                        dump($cityName);
                                    }
                                }
                            }
                        }
                    }
                }
                elseif ($node->nodeName === "h4"){
                    $countryName = $node->nodeValue;
                }
            }
        }
    }
}
