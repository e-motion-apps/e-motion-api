<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Country;
use Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CountryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCountryIndex(): void
    {
        Country::factory()->count(3)->create();

        $this->get("/countries")->assertInertia(
            fn(Assert $page) => $page
                ->component(value: "Countries")
                ->has("countries.data", 3),
        );
    }

    public function testCountryStore(): void
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

    public function testCountryStoreIsRefusingDuplicate(): void
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
        yield "country with empty credentials is invalid" => [
            "data" => [
                "name" => null,
                "longitude" => null,
                "latitude" => null,
                "iso" => null,
            ],
            "expectedErrors" => ["name", "latitude", "longitude", "iso"],
        ];

        yield "country with incorrect name is invalid" => [
            "data" => [
                "name" => "poland",
                "longitude" => 21.555,
                "latitude" => 55.234,
                "iso" => "pl",
            ],
            "expectedErrors" => ["name"],
        ];

        yield "country with incorrect longitude is invalid" => [
            "data" => [
                "name" => "Poland",
                "longitude" => "21string",
                "latitude" => 55.234,
                "iso" => "pl",
            ],
            "expectedErrors" => ["longitude"],
        ];

        yield "country with incorrect latitude is invalid" => [
            "data" => [
                "name" => "Poland",
                "longitude" => 21.555,
                "latitude" => "55.234string",
                "iso" => "pl",
            ],
            "expectedErrors" => ["latitude"],
        ];

        yield "country with incorrect iso is invalid" => [
            "data" => [
                "name" => "Poland",
                "longitude" => 21.555,
                "latitude" => 55.234,
                "iso" => "Pl",
            ],
            "expectedErrors" => ["iso"],
        ];
    }

    public function testCountryUpdate(): void
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

    public function testCountryUpdateIsRefusingDuplicateName(): void
    {
        $country = Country::query()->create([
            "name" => "Romania",
            "latitude" => -55.54323,
            "longitude" => 42.3721,
            "iso" => "rom",
        ]);

        $countryToCompare = Country::query()->create([
            "name" => "Poland",
            "latitude" => 44.543,
            "longitude" => -43.122,
            "iso" => "pl",
        ]);

        $updateCountryToCompare = [
            "name" => $countryToCompare["name"],
            "latitude" => $countryToCompare["latitude"],
            "longitude" => $countryToCompare["longitude"],
            "iso" => $country["iso"],
        ];

        $response = $this->patch(route(name:"countries.update", parameters: ["country" => $countryToCompare]), data: $updateCountryToCompare);
        $response->assertSessionHasErrors(["iso"]);
    }

    public function testCountryDestroy(): void
    {
        $country = Country::factory()->create();

        $this->delete(route(name:"countries.destroy", parameters: $country));

        $this->assertDatabaseMissing(table: "countries", data: $country->toArray());
    }
}
