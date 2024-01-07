<?php

declare(strict_types=1);

namespace Tests\Unit\Importers;

use App\Importers\BerylDataImporter;
use App\Services\MapboxGeocodingService;
use Database\Seeders\ProviderSeeder;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class BerylDataImporterTest extends TestCase
{
    private $dataImporter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ProviderSeeder::class);
        $mockResponseBody = '';

        $mockHandler = new MockHandler([
            new Response(200, [], $mockResponseBody),
        ]);

        $handlerStack = HandlerStack::create($mockHandler);
        $mockHttpClient = new Client(["handler" => $handlerStack]);

//        $mockGoogleTranslate = $this->createMock(GoogleTranslate::class);

        $mockMapboxService = $this->createMock(MapboxGeocodingService::class);
        $mockMapboxService->method("getCoordinatesFromApi")
            ->willReturn([
                "latitude" => "mocked_latitude",
                "longitude" => "mocked_longitude",
            ]);

        $this->dataImporter = new class($mockHttpClient, $mockMapboxService) extends BerylDataImporter {
            protected function createImportInfoDetails($code, $providerName): void
            {
            }
        };
    }

    public function testAddAllRecordsToDatabase(): void
    {
        $this->dataImporter->extract();
        $this->dataImporter->transform();

        $this->assertDatabaseHas("city_without_assigned_countries", [

            "city_name" => "West Midlands",
            "country_name" => "United Kingdom",
        ]);

        $this->assertDatabaseCount('city_without_assigned_countries', 1);
    }
}
