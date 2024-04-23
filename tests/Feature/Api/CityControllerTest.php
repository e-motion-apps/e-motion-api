<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\City;
use App\Models\Country;
use App\Models\User;
use Generator;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CityControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $adminUser = User::factory()->create();
        Sanctum::actingAs($adminUser, ["HasAdminRole"]);
    }

    public function testCitiesArrayIsReturned(): void
    {
        City::factory()->count(3)->create();

        $response = $this->getJson("/api/admin/cities");

        $response->assertStatus(200)->assertJsonStructure([
            "cities" => ["*" => []],
            "providers" => ["*" => []],
            "countries" => ["*" => []],
            "citiesWithoutAssignedCountry" => ["*" => []],
        ]);
    }

    public function testCityCanBeCreated(): void
    {
        $country = Country::factory()->create();

        $city = [
            "name" => "Legnica",
            "latitude" => 44.543,
            "longitude" => -43.122,
            "country_id" => $country->id,
        ];

        $this->postJson("/api/admin/cities", $city);

        $this->assertDatabaseHas("cities", $city);
    }

    public function testCityCannotBeCreatedBecauseFieldsAlreadyExist(): void
    {
        $country = Country::factory()->create();

        $city = [
            "name" => "Legnica",
            "latitude" => 44.543,
            "longitude" => -43.122,
            "country_id" => $country->id,
        ];

        City::query()->create([
            "name" => $city["name"],
            "latitude" => -55.54323,
            "longitude" => 42.3721,
            "country_id" => $country->id,
        ])->toArray();

        $this->postJson("/api/admin/cities", $city);

        $this->assertDatabaseMissing("cities", $city);
    }

    /**
     * @dataProvider invalidCityDataProvider
     */
    public function testCityCannotBeCreatedWithInvalidData(array $data, array $expectedErrors): void
    {
        $response = $this->postJson("/api/admin/cities", $data);

        $response->assertJsonValidationErrors($expectedErrors);
    }

    /**
     * @dataProvider invalidCityDataProvider
     */
    public function testCityCannotBeUpdatedWithInvalidData(array $data, array $expectedErrors): void
    {
        $city = City::factory()->create();

        $response = $this->patchJson("/api/admin/cities/{$city->id}", $data);

        $response->assertJsonValidationErrors($expectedErrors);
    }

    public static function invalidCityDataProvider(): Generator
    {
        yield "city with empty credentials" => [
            "data" => [
                "name" => null,
                "longitude" => null,
                "latitude" => null,
            ],
            "expectedErrors" => ["name", "latitude", "longitude"],
        ];

        yield "city with incorrect name" => [
            "data" => [
                "name" => "legnica",
                "longitude" => 21.555,
                "latitude" => 55.234,
            ],
            "expectedErrors" => ["name"],
        ];

        yield "city with incorrect longitude" => [
            "data" => [
                "name" => "Legnica",
                "longitude" => "21string",
                "latitude" => 55.234,
            ],
            "expectedErrors" => ["longitude"],
        ];

        yield "city with incorrect latitude" => [
            "data" => [
                "name" => "Legnica",
                "longitude" => 21.555,
                "latitude" => "55.234string",
            ],
            "expectedErrors" => ["latitude"],
        ];
    }

    public function testCityCanBeUpdated(): void
    {
        $country = Country::factory()->create();

        $data = [
            "name" => "Legnica",
            "latitude" => 44.543,
            "longitude" => -43.122,
            "country_id" => $country->id,
        ];

        $city = City::factory()->create();

        $this->patchJson("/api/admin/cities/{$city->id}", $data);

        $this->assertDatabaseHas("cities", $data);
    }

    public function testCityCannotBeUpdatedBecauseNameAlreadyExistInOtherCity(): void
    {
        City::factory()->create([
            "name" => "Legnica",
        ]);

        $cityToUpdate = City::factory()->create([
            "name" => "Wrocław",
        ]);

        $response = $this->patchJson("/api/admin/cities/{$cityToUpdate->id}", [
            "name" => "Legnica",
            "latitude" => 32.444,
            "longitude" => 44.222,
            "country_id" => 1,
        ]);

        $response->assertStatus(422)->assertJsonValidationErrorFor("name");
    }

    public function testCityCanBeDeleted(): void
    {
        $city = City::factory()->create();

        $this->deleteJson("/api/admin/cities/{$city->id}");

        $this->assertDatabaseMissing("cities", $city->toArray());
    }
}
