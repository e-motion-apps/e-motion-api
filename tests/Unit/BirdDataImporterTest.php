<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Importers\BirdDataImporter;
use App\Services\MapboxGeocodingService;
use Database\Seeders\CitiesAndCountriesSeeder;
use Database\Seeders\ProviderSeeder;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\MockObject\Exception;
use Tests\TestCase;

class BirdDataImporterTest extends TestCase
{
    use RefreshDatabase;

    public function testExtractWithSuccessfulResponse(): void
    {
        $mockResponseBody = "let features = [
					  {
			position: new google.maps.LatLng(-31.9523123, 115.861309),
			type: 'hq'
		  }];";

        $mockHandler = new MockHandler([
            new Response(200, [], $mockResponseBody),
        ]);
        $handlerStack = HandlerStack::create($mockHandler);
        $mockHttpClient = new Client(['handler' => $handlerStack]);

        $mockMapboxService = $this->createMock(MapboxGeocodingService::class);

        $dataImporter = new BirdDataImporter($mockHttpClient, $mockMapboxService);
        $result = $dataImporter->extract();

        $this->assertFalse($result->hasStoppedExecution());
    }

    /**
     * @throws Exception
     */
    public function testExtractWithFailedResponse(): void
    {
        $mockHandler = new MockHandler([
            new RequestException("Error communicating with the server", new Request("GET", "https://www.bird.co/map/")),
        ]);

        $handlerStack = HandlerStack::create($mockHandler);
        $mockHttpClient = new Client(['handler' => $handlerStack]);

        $mockMapboxService = $this->createMock(MapboxGeocodingService::class);

        $dataImporter = new class($mockHttpClient, $mockMapboxService) extends BirdDataImporter {
            protected function createImportInfoDetails($code, $providerName): void
            {
            }
        };

        $result = $dataImporter->extract();

        $this->assertTrue($result->hasStoppedExecution());
    }

    public function testCityAddsToCitiesDatabase(): void
    {
        $this->seed(ProviderSeeder::class);
        $this->seed(CitiesAndCountriesSeeder::class);

        $mockResponseBody = "let features = [
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
        }
    ];";

        $mockHandler = new MockHandler([
            new Response(200, [], $mockResponseBody),
        ]);

        $handlerStack = HandlerStack::create($mockHandler);
        $mockHttpClient = new Client(['handler' => $handlerStack]);

        $mockMapboxService = $this->createMock(MapboxGeocodingService::class);
        $mockMapboxService->method('getPlaceFromApi')->willReturn(["Perth", "Australia"], ["Ottawa", "Canada"], ["Lincoln", "United States"]);

        $dataImporter = new class($mockHttpClient, $mockMapboxService) extends BirdDataImporter {
            protected function createImportInfoDetails($code, $providerName): void
            {
            }
        };

        $dataImporter->extract();
        $dataImporter->transform();

        $this->assertDatabaseHas('cities', [
            "name" => "Perth",
            "latitude" => "-31.9523123",
            "longitude" => "115.861309",
        ]);
        $this->assertDatabaseHas('cities', [
            "name" => "Ottawa",
            "latitude" => "45.421144",
            "longitude" => "-75.690057",
        ]);
        $this->assertDatabaseHas('cities', [
            "name" => "Lincoln",
            "latitude" => "40.813616",
            "longitude" => "-96.7025955",
        ]);
    }
}
