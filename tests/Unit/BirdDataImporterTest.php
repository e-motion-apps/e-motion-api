<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Importers\BirdDataImporter;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class BirdDataImporterTest extends TestCase
{
    public function testExtractWithSuccessfulResponse(): void
    {
        $mockHttpClient = $this->createMock(Client::class);

        $mockResponse = new Response(200, [], "Mocked HTML Content");

        $mockHttpClient->method("get")->willReturn($mockResponse);

        $dataImporter = new BirdDataImporter($mockHttpClient);

        $result = $dataImporter->extract();

        $this->assertInstanceOf(BirdDataImporter::class, $result);
        $this->assertFalse($result->hasStoppedExecution());
    }

    /**
     * @throws Exception
     */
    public function testExtractWithFailedResponse(): void
    {
        $mockHttpClient = $this->createMock(Client::class);

        $mockHttpClient->method("get")->willThrowException(new RequestException("Error communicating with the server", new \GuzzleHttp\Psr7\Request("GET", "https://www.bird.co/map/")));

        $dataImporter = new class($mockHttpClient) extends BirdDataImporter {
            protected function createImportInfoDetails($code, $providerId): void
            {
            }
        };

        $result = $dataImporter->extract();

        $this->assertInstanceOf(BirdDataImporter::class, $result);
        $this->assertTrue($result->hasStoppedExecution());
    }
}
