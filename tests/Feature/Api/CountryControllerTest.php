<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Country;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CountryControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $adminUser = User::factory()->create();
        Sanctum::actingAs($adminUser, ["HasAdminRole"]);
    }

    public function testCountriesArrayIsReturned(): void
    {
        Country::factory()->count(3)->create();

        $response = $this->getJson("/api/admin/countries");
        $response->assertJsonStructure([
            "countries" => [
                "*" => [
                    "id",
                    "name",
                    "latitude",
                    "longitude",
                    "iso",
                ],
            ],
        ]);
    }

    public function testCountryCanBeCreated(): void
    {
        $country = [
            "name" => "Poland",
            "latitude" => 44.543,
            "longitude" => -43.122,
            "iso" => "pl",
        ];

        $this->postJson("/api/admin/countries", $country);

        $this->assertDatabaseHas("countries", $country);
    }

    public function testCountryCannotBeCreatedBecauseFieldsAlreadyExist(): void
    {
        $countryData = [
            "name" => "Poland",
            "latitude" => 44.543,
            "longitude" => -43.122,
            "iso" => "pl",
        ];

        $response = $this->postJson("/api/admin/countries", $countryData);

        $response->assertStatus(Response::HTTP_CREATED);

        $response = $this->postJson("/api/admin/countries", $countryData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(["name", "iso"]);
    }

    /**
     * @dataProvider invalidCountryDataProvider
     */
    public function testCountryCannotBeCreatedWithInvalidData(array $data, array $expectedErrors): void
    {
        $response = $this->postJson("/api/admin/countries", $data);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors($expectedErrors);
    }

    /**
     * @dataProvider invalidCountryDataProvider
     */
    public function testCountryCannotBeUpdatedWithInvalidData(array $data, array $expectedErrors): void
    {
        $country = Country::factory()->create();

        $response = $this->patchJson("/api/admin/countries/{$country->id}", $data);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors($expectedErrors);
    }

    public static function invalidCountryDataProvider(): array
    {
        return [
            "country with empty credentials" => [
                "data" => [
                    "name" => null,
                    "longitude" => null,
                    "latitude" => null,
                    "iso" => null,
                ],
                "expectedErrors" => ["name", "latitude", "longitude", "iso"],
            ],
            "country with incorrect name" => [
                "data" => [
                    "name" => "poland",
                    "longitude" => 21.555,
                    "latitude" => 55.234,
                    "iso" => "pl",
                ],
                "expectedErrors" => ["name"],
            ],
            "country with incorrect longitude" => [
                "data" => [
                    "name" => "Poland",
                    "longitude" => "21string",
                    "latitude" => 55.234,
                    "iso" => "pl",
                ],
                "expectedErrors" => ["longitude"],
            ],
            "country with incorrect latitude" => [
                "data" => [
                    "name" => "Poland",
                    "longitude" => 21.555,
                    "latitude" => "55.234string",
                    "iso" => "pl",
                ],
                "expectedErrors" => ["latitude"],
            ],
            "country with incorrect iso" => [
                "data" => [
                    "name" => "Poland",
                    "longitude" => 21.555,
                    "latitude" => 55.234,
                    "iso" => "Pl",
                ],
                "expectedErrors" => ["iso"],
            ],
        ];
    }

    public function testCountryCanBeUpdated(): void
    {
        $data = [
            "name" => "Poland",
            "latitude" => 44.543,
            "longitude" => -43.122,
            "iso" => "pl",
        ];

        $country = Country::factory()->create();

        $this->patchJson("/api/admin/countries/{$country->id}", $data);

        $this->assertDatabaseHas("countries", $data);
    }

    public function testCountryCannotBeUpdatedBecauseIsoAlreadyExistInOtherCountry(): void
    {
        Country::factory()->create([
            "name" => "Poland",
            "iso" => "pl",
        ]);

        $countryToUpdate = Country::factory()->create([
            "name" => "Romania",
            "iso" => "rom",
        ]);

        $response = $this->patch("/api/admin/countries/{$countryToUpdate->id}", [
            "name" => "Romania",
            "latitude" => 32.444,
            "longitude" => 44.222,
            "iso" => "pl",
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrorFor("iso");
    }

    public function testCountryCannotBeUpdatedBecauseNameAlreadyExistInOtherCountry(): void
    {
        Country::factory()->create([
            "name" => "Poland",
            "iso" => "pl",
        ]);

        $countryToUpdate = Country::factory()->create([
            "name" => "Romania",
            "iso" => "rom",
        ]);

        $response = $this->patchJson("/api/admin/countries/{$countryToUpdate->id}", [
            "name" => "Poland",
            "latitude" => 32.444,
            "longitude" => 44.222,
            "iso" => "rom",
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrorFor("name");
    }

    public function testCountryCanBeDeleted(): void
    {
        $country = Country::factory()->create();

        $this->delete("/api/admin/countries/{$country->id}");

        $this->assertDatabaseMissing("countries", $country->toArray());
    }
}
