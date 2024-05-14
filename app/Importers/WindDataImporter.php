<?php

declare(strict_types=1);

namespace App\Importers;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class WindDataImporter extends DataImporter
{
    protected Crawler $sections;

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://wind.yango.com/official-website/#/rider?lang=gb");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());

            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);
        $this->sections = $crawler->filter("body");

        if (count($this->sections) === 0) {
            $this->createImportInfoDetails("204", self::getProviderName());
            $this->stopExecution = true;
        }

        return $this;
    }

    public function transform(): void
    {
        foreach ($this->sections as $section) {
            foreach ($section->childNodes as $node) {
                if ($node->nodeName === "script" && $node->hasAttribute("src")) {
                    $scriptSrc = $node->getAttribute("src");
                }
            }
        }
        $jsSrc = "https://wind.yango.com/official-website/" . $scriptSrc;
        $jsContent = file_get_contents($jsSrc);

        $substring_start = strpos($jsContent, "YOUR SECRET SUPERPOWER?");
        $substring_start += strlen("YOUR SECRET SUPERPOWER?");
        $size = strpos($jsContent, "Get the app now", $substring_start) - $substring_start;
        $substring = substr($jsContent, $substring_start, $size);
        $patternCity = "/\[\"(.*?)\"]/";
        $patternCountry = '/:"(.*?)",/';
        preg_match_all($patternCity, $substring, $matchesCities);
        $extractedCities = $matchesCities[1];
        preg_match_all($patternCountry, $substring, $matchesCountries);
        $extractedCountries = $matchesCountries[1];
        $extractedCities = str_replace('"', "", $extractedCities);

        $resultArray = array_map(null, $extractedCities, $extractedCountries);

        foreach ($resultArray as $result) {
            $cityName = explode(",", $result[0]);
            $countryName = $result[1];

            foreach ($cityName as $city) {
                if ($countryName === "UK") {
                    $countryName = "United Kingdom";
                }

                if ($countryName === "Korea") {
                    $countryName = "South Korea";
                }
                $provider = $this->load(ucfirst($countryName), $city);

                if ($provider !== "") {
                    $existingCityProviders[] = $provider;
                }
            }
        }
        $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
    }
}
