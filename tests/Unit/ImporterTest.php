<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Importers\BirdDataImporter;
use App\Models\City;
use App\Models\CityProvider;
use App\Models\Country;
use App\Services\MapboxGeocodingService;
use Database\Seeders\CitiesAndCountriesSeeder;
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
        $mockHttpClient = new Client(["handler" => $handlerStack]);

        $mockMapboxService = $this->createMock(MapboxGeocodingService::class);
        $mockMapboxService->method("getPlaceFromApi")->willReturn(["Perth", "Australia"], ["Ottawa", "Canada"], ["Lincoln", "United States"]);

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

    public function testAddToCityProviderDbWhenCityExists(): void
    {
        $this->seed(CitiesAndCountriesSeeder::class);

        $this->dataImporter->extract();
        $this->dataImporter->transform();

        $this->assertDatabaseHas("cities", [
            "name" => "Perth",
            "latitude" => "-31.9523123",
            "longitude" => "115.861309",
        ]);
        $this->assertDatabaseHas("cities", [
            "name" => "Ottawa",
            "latitude" => "45.421144",
            "longitude" => "-75.690057",
        ]);
        $this->assertDatabaseHas("cities", [
            "name" => "Lincoln",
            "latitude" => "40.813616",
            "longitude" => "-96.7025955",
        ]);
    }

    public function testAddToCityWithoutAssignedCountryDbWhenCityAndCountryMissing(): void
    {
        $this->dataImporter->extract();
        $this->dataImporter->transform();

        $this->assertDatabaseHas("city_without_assigned_countries", [
            "city_name" => "Perth",
        ]);
        $this->assertDatabaseHas("city_without_assigned_countries", [
            "city_name" => "Ottawa",
        ]);
        $this->assertDatabaseHas("city_without_assigned_countries", [
            "city_name" => "Lincoln",
        ]);
    }

    public function testAddToCityProviderDbAndCityDbWhenCountryExistsButCityMissing(): void
    {
        $this->seed(CitiesAndCountriesSeeder::class);

        City::query()->where("name", "Perth")->delete();

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

        $perth = City::query()->where("name", "Perth")->first();

        $this->assertDatabaseHas("city_providers", [
            "provider_name" => "Bird",
            "city_id" => $perth->id,
        ]);
    }

    public function testDeleteRecordWhenProviderNoLongerInThisCity(): void
    {
        $this->seed(CitiesAndCountriesSeeder::class);
        $country = Country::query()->where("name", "Poland")->first();

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
            "city_id" => $city->id,
            "provider_name" => "Bird",
        ]);

        $this->dataImporter->extract();
        $this->dataImporter->transform();

        $this->assertDatabaseMissing("city_providers", [
            "city_id" => $city->id,
            "provider_name" => "Bird",
        ]);
    }
}
