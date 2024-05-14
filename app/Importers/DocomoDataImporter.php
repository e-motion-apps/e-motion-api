<?php

declare(strict_types=1);

namespace App\Importers;

use App\Enums\ServicesEnum;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class DocomoDataImporter extends DataImporter
{
    private const COUNTRY_NAME = "Japan";

    protected Crawler $sections;
    private array $services = [ServicesEnum::Bike];

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://docomo-cycle.jp/?lang=en");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());

            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);
        $this->sections = $crawler->filter('div[class="show-all"]');

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

        $cities = $this->sections->filter('span[class="composite"]');

        $cityNames = [];

        foreach ($cities as $city) {
            $cityNames[] = $city->textContent;
        }

        foreach ($cityNames as $cityName) {
            $this->load($cityName, self::COUNTRY_NAME, $lat = "", $long = "", $this->services);
        }
    }
}
