<?php

declare(strict_types=1);

namespace App\Importers;

use App\Enums\ServicesEnum;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class BeamDataImporter extends DataImporter
{
    protected Crawler $sections;

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://partner.ridebeam.com/cities");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());
            $this->stopExecution = true;

            return $this;
        }
        $crawler = new Crawler($html);
        $this->sections = $crawler->filter("div.find-beam-box");

        if (count($this->sections) === 0) {
            $this->createImportInfoDetails("204", self::getProviderName());
            $this->stopExecution = true;
        }

        return $this;
    }

    public function transform(): void
    {
        $escooterImageUrl = "https://assets-global.website-files.com/63c4acbedbab5dea8b1b98cd/63d8a5b60da91e7d71298637_map-vehicle-saturn.png";
        $bikeImageUrl = "https://assets-global.website-files.com/63c4acbedbab5dea8b1b98cd/63d8a5b7f06e4a42de02241b_map-vehicle-saturn-apollo.png";

        if ($this->stopExecution) {
            return;
        }
        $existingCityProviders = [];

        foreach ($this->sections as $section) {
            $crawler = new Crawler($section);
            $countryName = $crawler->filter('h4[class="find-beam-title-map"]')->text();

            $columns = $crawler->filter('div[class="beam-col"], div[class="beam-col-main-box"]');

            foreach ($columns as $column) {
                $columnCrawler = new Crawler($column);
                $services = [];
                $images = $columnCrawler->filter("img");

                foreach ($images as $image) {
                    if ($image->getAttribute("src") === $escooterImageUrl) {
                        $services[] = ServicesEnum::Escooter;
                    } else if ($image->getAttribute("src") === $bikeImageUrl) {
                        $services[] = ServicesEnum::Bike;
                    }
                }

                $cityNamesHtml = $columnCrawler->filter("p")->html();
                $cityNamesArray = explode("<br>", $cityNamesHtml);

                foreach ($cityNamesArray as $cityName) {
                    $cityName = strip_tags($cityName);
                    $search = ["&nbsp;•", "&nbsp;"];
                    $cityName = str_replace($search, "", $cityName);
                    $cityName = trim($cityName);

                    if ($countryName === "Korea") {
                        $countryName = "South Korea";
                    }

                    $provider = $this->load($cityName, $countryName, $lat = "", $long = "", $services);

                    if ($provider) {
                        $existingCityProviders[] = $provider;
                    }
                }
            }
        }
        $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
    }
}
