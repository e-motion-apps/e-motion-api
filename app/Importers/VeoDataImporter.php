<?php

declare(strict_types=1);

namespace App\Importers;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class VeoDataImporter extends DataImporter
{
    private const COUNTRY_NAME = "United States";

    protected Crawler $sections;
    private array $usaStates = [
        "Alabama", "Alaska", "Arizona", "Arkansas", "California",
        "Colorado", "Connecticut", "Delaware", "Florida", "Georgia",
        "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas",
        "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts",
        "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana",
        "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico",
        "New York", "North Carolina", "North Dakota", "Ohio", "Oklahoma",
        "Oregon", "Pennsylvania", "Rhode Island", "South Carolina",
        "South Dakota", "Tennessee", "Texas", "Utah", "Vermont",
        "Virginia", "Washington, DC", "West Virginia", "Wisconsin", "Wyoming",
    ];

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://www.veoride.com/locations/");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());

            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);
        $this->sections = $crawler->filter("body > div > div .elementor-226069 > div .elementor-row");

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
            if ($section->nodeName === "div") {
                foreach ($section->childNodes as $city) {
                    $cityName = str_replace(["\t", "\u{200B}"], "", $city->nodeValue);
                    $cityName = str_replace("\n", " ", $cityName);
                    $cityName = preg_replace('/\s{2,}/', "  ", $cityName);
                    $arrayOfCitiesNames = explode("  ", $cityName);
                    $arrayOfCitiesNames = array_filter($arrayOfCitiesNames, fn(string $value): bool => (stripos($value, "University") === false) && (stripos($value, "College") === false));
                    $arrayOfCitiesNames = array_filter($arrayOfCitiesNames);
                    $arrayOfCitiesNames = array_diff($arrayOfCitiesNames, $this->usaStates);

                    foreach ($arrayOfCitiesNames as $cityName) {
                        $cityName = trim($cityName);

                        if ($cityName !== "") {
                            $provider = $this->load(self::COUNTRY_NAME, $cityName);

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
}
