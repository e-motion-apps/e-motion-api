<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Country;
use Generator;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CountryControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function testCountriesViewCanBeRendered(): void
    {
        Country::factory()->count(3)->create();

        $this->get("/countries")->assertInertia(
            fn(Assert $page) => $page
                ->component(value: "Countries")
                ->has("countries.data", 3),
        );
    }

    public function testCountryCanBeCreated(): void
    {
        $country = [
            "name" => "Poland",
            "latitude" => 44.543,
            "longitude" => -43.122,
            "iso" => "pl",
        ];

        $this->post(route(name:"countries.store"), data: $country);

        $this->assertDatabaseHas(table:"countries", data: $country);
    }

    public function testCountryCannotBeCreatedBecauseFieldsAlreadyExist(): void
    {
        $country = [
            "name" => "Poland",
            "latitude" => 44.543,
            "longitude" => -43.122,
            "iso" => "pl",
        ];

        Country::query()->create([
            "name" => $country["name"],
            "latitude" => -55.54323,
            "longitude" => 42.3721,
            "iso" => $country["iso"],
        ])->toArray();

        $this->post(route(name:"countries.store"), data: $country);

        $this->assertDatabaseMissing(table: "countries", data: $country);
    }

    /**
     * @dataProvider invalidCountryDataProvider
     */
    public function testCountryCannotBeCreatedWithInvalidData(array $data, array $expectedErrors): void
    {
        $response = $this->post(route("countries.store"), $data);

        $response->assertSessionHasErrors($expectedErrors);
    }

    /**
     * @dataProvider invalidCountryDataProvider
     */
    public function testCountryCannotBeUpdatedWithInvalidData(array $data, array $expectedErrors): void
    {
        $country = Country::factory()->create();

        $response = $this->patch(route("countries.update", ["country" => $country]), $data);

        $response->assertSessionHasErrors($expectedErrors);
    }

    public static function invalidCountryDataProvider(): Generator
    {
        yield "country with empty credentials" => [
            "data" => [
                "name" => null,
                "longitude" => null,
                "latitude" => null,
                "iso" => null,
            ],
            "expectedErrors" => ["name", "latitude", "longitude", "iso"],
        ];

        yield "country with incorrect name" => [
            "data" => [
                "name" => "poland",
                "longitude" => 21.555,
                "latitude" => 55.234,
                "iso" => "pl",
            ],
            "expectedErrors" => ["name"],
        ];

        yield "country with incorrect longitude" => [
            "data" => [
                "name" => "Poland",
                "longitude" => "21string",
                "latitude" => 55.234,
                "iso" => "pl",
            ],
            "expectedErrors" => ["longitude"],
        ];

        yield "country with incorrect latitude" => [
            "data" => [
                "name" => "Poland",
                "longitude" => 21.555,
                "latitude" => "55.234string",
                "iso" => "pl",
            ],
            "expectedErrors" => ["latitude"],
        ];

        yield "country with incorrect iso" => [
            "data" => [
                "name" => "Poland",
                "longitude" => 21.555,
                "latitude" => 55.234,
                "iso" => "Pl",
            ],
            "expectedErrors" => ["iso"],
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

        $this->patch(route(name:"countries.update", parameters: ["country" => $country]), $data);

        $this->assertDatabaseHas(table:"countries", data: $data);
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

        $this->patch(route(name:"countries.update", parameters: ["country" => $countryToUpdate]), data: [
            "name" => "Romania",
            "latitude" => 32.444,
            "longitude" => 44.222,
            "iso" => "pl",
        ])->assertSessionHasErrors(["iso"]);
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

        $this->patch(route(name:"countries.update", parameters: ["country" => $countryToUpdate]), data: [
            "name" => "Poland",
            "latitude" => 32.444,
            "longitude" => 44.222,
            "iso" => "rom",
        ])->assertSessionHasErrors(["name"]);
    }

    public function testCountryCanBeDeleted(): void
    {
        $country = Country::factory()->create();

        $this->delete(route(name:"countries.destroy", parameters: $country));

        $this->assertDatabaseMissing(table: "countries", data: $country->toArray());
    }
}
