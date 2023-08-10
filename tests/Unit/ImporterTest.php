<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Importers\BirdDataImporter;
use App\Models\City;
use App\Models\CityProvider;
use App\Models\Country;
use App\Services\MapboxGeocodingService;
use Database\Seeders\ProviderSeeder;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\Exception;
use Tests\TestCase;

class ImporterTest extends TestCase
{
    private $dataImporter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ProviderSeeder::class);
        $mockResponseBody = "let features = [
        {
            position: new google.maps.LatLng(-31.9523123, 115.861309),
            type: 'hq'
        },
    ];";

        $mockHandler = new MockHandler([
            new Response(200, [], $mockResponseBody),
        ]);

        $handlerStack = HandlerStack::create($mockHandler);
        $mockHttpClient = new Client(["handler" => $handlerStack]);

        $mockMapboxService = $this->createMock(MapboxGeocodingService::class);
        $mockMapboxService->method("getPlaceFromApi")->willReturn(["Perth", "Australia"]);
        $mockMapboxService->method("getCoordinatesFromApi")
            ->willReturn([
                "latitude" => "mocked_latitude",
                "longitude" => "mocked_longitude",
            ]);

        $this->dataImporter = new class($mockHttpClient, $mockMapboxService) extends BirdDataImporter {
            protected function createImportInfoDetails($code, $providerName): void
            {
            }
        };
    }

    public function testExtractWithSuccessfulResponse(): void
    {
        $result = $this->dataImporter->extract();

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
        $mockHttpClient = new Client(["handler" => $handlerStack]);

        $mockMapboxService = $this->createMock(MapboxGeocodingService::class);

        $dataImporter = new class($mockHttpClient, $mockMapboxService) extends BirdDataImporter {
            protected function createImportInfoDetails($code, $providerName): void
            {
            }
        };

        $result = $dataImporter->extract();

        $this->assertTrue($result->hasStoppedExecution());
    }

    public function testAddToCityWithoutAssignedCountryDbWhenCityAndCountryMissing(): void
    {
        $this->dataImporter->extract();
        $this->dataImporter->transform();

        $this->assertDatabaseHas("city_without_assigned_countries", [
            "city_name" => "Perth",
        ]);
    }

    public function testAddToCityProviderDbAndCityDbWhenCountryExistsButCityMissing(): void
    {
        Country::query()->create([
            "name" => "Australia",
            "latitude" => "-27.00000000",
            "longitude" => "133.00000000",
            "iso" => "au",
        ]);

        $this->assertDatabaseMissing("cities", [
            "name" => "Perth",
        ]);

        $this->dataImporter->extract();
        $this->dataImporter->transform();

        $this->assertDatabaseHas("cities", [
            "name" => "Perth",
            "latitude" => "-31.9523123",
            "longitude" => "115.861309",
        ]);

        $this->assertDatabaseHas("city_providers", [
            "provider_name" => "Bird",
            "city_id" => "1",
        ]);
    }

    public function testAddToCityProviderDbWhenCityExists(): void
    {
        $country = Country::query()->create([
            "name" => "Australia",
            "latitude" => "-27.00000000",
            "longitude" => "133.00000000",
            "iso" => "au",
        ]);

        $city = City::query()->create([
            "name" => "Perth",
            "latitude" => "-31.9523123",
            "longitude" => "115.861309",
            "country_id" => $country->id,
        ]);
        $cityId = $city->id;

        $this->dataImporter->extract();
        $this->dataImporter->transform();

        $this->assertDatabaseHas("city_providers", [
            "provider_name" => "Bird",
            "city_id" => $cityId,
        ]);
    }

    public function testDeleteRecordWhenProviderNoLongerInThisCity(): void
    {
        $country = Country::query()->create([
            "name" => "Poland",
            "latitude" => "52.00000000",
            "longitude" => "20.00000000",
            "iso" => "pl",
        ]);

        $city = City::query()->create([
            "name" => "Legnica",
            "latitude" => "51.271995",
            "longitude" => "15.950674",
            "country_id" => $country->id,
        ]);
        $cityId = $city->id;

        CityProvider::query()->updateOrCreate([
            "city_id" => $cityId,
            "provider_name" => "Bird",
            "created_by" => "scrapper",
        ]);

        $this->assertDatabaseHas("city_providers", [
            "city_id" => $cityId,
            "provider_name" => "Bird",
        ]);

        $this->dataImporter->extract();
        $this->dataImporter->transform();

        $this->assertDatabaseMissing("city_providers", [
            "city_id" => $cityId,
            "provider_name" => "Bird",
        ]);
    }
}
