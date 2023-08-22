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
                if ($node->nodeName === "script" && $node->hasAttribute('src')) {
                    $scriptSrc = $node->getAttribute('src');
                }
            }
        }
        $jsSrc = 'https://wind.yango.com/official-website/' . $scriptSrc;
        $jsContent = file_get_contents($jsSrc);
        $pattern = '/var\s+(\w+)\s*=\s*\[([^\]]+)\]/';
        preg_match_all($pattern, $jsContent, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $arrayName = $match[1];
            $arrayContent = $match[2];

            $arrayItems = explode(',', $arrayContent);
            dump($arrayItems);
        }
    }
}
