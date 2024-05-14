<?php

declare(strict_types=1);

namespace App\Importers;

use App\Enums\ServicesEnum;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class BaqmeDataImporter extends DataImporter
{
    private const COUNTRY_NAME = "Netherlands";

    protected Crawler $sections;
    private array $services = [ServicesEnum::Cargo];

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://www.baqme.com/en/");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());
            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);
        $this->sections = $crawler->filter("div.coming");

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
            $city = new Crawler($section);
            $cityData = $city->filter("b")->html();
            $cityArray = explode("<br>", $cityData);

            if (count($cityArray) > 1 && $cityArray[1] === "Coming soon") {
                continue;
            }
            $cityName = $cityArray[0];
            $provider = $this->load(self::COUNTRY_NAME, $cityName, $this->services);

            if ($provider) {
                $existingCityProviders[] = $provider;
            }
        }

        $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
    }
}
