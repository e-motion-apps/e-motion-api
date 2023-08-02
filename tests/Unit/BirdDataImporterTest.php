<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Importers\BirdDataImporter;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class BirdDataImporterTest extends TestCase
{
    public function testExtractWithSuccessfulResponse(): void
    {
        $mockResponse = new Response(200, [], "let features = [
					  {
			position: new google.maps.LatLng(-31.9523123, 115.861309),
			type: 'hq'
		  },
					  {
			position: new google.maps.LatLng(45.4215296, -75.6971931),
			type: 'hq'
		  },
					  {
			position: new google.maps.LatLng(40.813616, -96.7025955),
			type: 'hq'
		  },];");

        $mockHttpClient = $this->createMock(Client::class);
        $mockHttpClient->method("get")->willReturn($mockResponse);

        $dataImporter = new BirdDataImporter($mockHttpClient);
        $result = $dataImporter->extract();

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
            protected function createImportInfoDetails($code, $providerName): void
            {
            }
        };

        $result = $dataImporter->extract();

        $this->assertTrue($result->hasStoppedExecution());
    }

    public function testParsingDataCorrectly():void
    {
        $html = "let features = [
        {
            position: new google.maps.LatLng(-31.9523123, 115.861309),
            type: 'hq'
        },
        {
            position: new google.maps.LatLng(45.4215296, -75.6971931),
            type: 'hq'
        },
        {
            position: new google.maps.LatLng(40.813616, -96.7025955),
            type: 'hq'
        },
    ];";

        $mockHttpClient = $this->createMock(Client::class);

        $dataImporter = new BirdDataImporter($mockHttpClient);

        $reflectionClass = new ReflectionClass(BirdDataImporter::class);
        $parseData = $reflectionClass->getMethod('parseData');
        $parseData->setAccessible(true);

        $result = $parseData->invoke($dataImporter, $html);

        $expectedResult = [
            "-31.9523123 115.861309",
            "45.4215296 -75.6971931",
            "40.813616 -96.7025955",
        ];
        $this->assertEquals($expectedResult, $result);



    }
}
