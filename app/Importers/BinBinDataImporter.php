<?php

declare(strict_types=1);

namespace App\Importers;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class BinBinDataImporter extends DataImporter
{
    private const LANGUAGE = "en";

    protected Crawler $sections;

    public function extract(): static
    {
        try {
            $response = $this->client->get("https://www.binbin.tech/lokasyonlar");
            $html = $response->getBody()->getContents();
        } catch (GuzzleException) {
            $this->createImportInfoDetails("400", self::getProviderName());

            $this->stopExecution = true;

            return $this;
        }

        $crawler = new Crawler($html);
        $this->sections = $crawler->filter(".location-wrapper .cities-information .div-block-35");

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
            $data = explode("|", $section->nodeValue);

            $countryName = $this->translate(trim($data[1]), self::LANGUAGE);

            if (trim($data[0]) === "Uşak") {
                $cityName = "Usak";
            } elseif (trim($data[0]) === "Murter") {
                continue;
            } else {
                $cityName = $this->translate(trim($data[0]), "en");
            }

            $provider = $this->load($cityName, $countryName);

            if ($provider !== "") {
                $existingCityProviders[] = $provider;
            }
        }
        $this->deleteMissingProviders(self::getProviderName(), $existingCityProviders);
    }
}
