<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\City;
use Generator;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CityControllerTest extends TestCase
{
    public function testCitiesViewCanBeRendered(): void
    {
        City::factory()->count(3)->create();

        $this->get("/cities")->assertInertia(
            fn(Assert $page) => $page
                ->component(value: "Cities")
                ->has("cities.data", 3),
        );
    }

    public function testCityCanBeCreated(): void
    {
        $city = [
            "name" => "Legnica",
            "latitude" => 44.543,
            "longitude" => -43.122,
        ];

        $this->post(route(name:"cities.store"), data: $city);

        $this->assertDatabaseHas(table:"cities", data: $city);
    }

    public function testCityCannotBeCreatedBecauseFieldsAlreadyExist(): void
    {
        $city = [
            "name" => "Legnica",
            "latitude" => 44.543,
            "longitude" => -43.122,
        ];

        City::query()->create([
            "name" => $city["name"],
            "latitude" => -55.54323,
            "longitude" => 42.3721,
        ])->toArray();

        $this->post(route(name:"cities.store"), data: $city);

        $this->assertDatabaseMissing(table: "cities", data: $city);
    }

    /**
     * @dataProvider invalidCityDataProvider
     */
    public function testCityCannotBeCreatedWithInvalidData(array $data, array $expectedErrors): void
    {
        $response = $this->post(route("cities.store"), $data);

        $response->assertSessionHasErrors($expectedErrors);
    }

    /**
     * @dataProvider invalidCityDataProvider
     */
    public function testCityCannotBeUpdatedWithInvalidData(array $data, array $expectedErrors): void
    {
        $city = City::factory()->create();

        $response = $this->patch(route("cities.update", ["city" => $city]), $data);

        $response->assertSessionHasErrors($expectedErrors);
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
        $data = [
            "name" => "Legnica",
            "latitude" => 44.543,
            "longitude" => -43.122,
        ];

        $city = City::factory()->create();

        $this->patch(route(name:"cities.update", parameters: ["city" => $city]), $data);

        $this->assertDatabaseHas(table:"cities", data: $data);
    }

    public function testCityCannotBeUpdatedBecauseNameAlreadyExistInOtherCity(): void
    {
        City::factory()->create([
            "name" => "Legnica",
        ]);

        $cityToUpdate = City::factory()->create([
            "name" => "Wrocław",
        ]);

        $this->patch(route(name:"cities.update", parameters: ["city" => $cityToUpdate]), data: [
            "name" => "Legnica",
            "latitude" => 32.444,
            "longitude" => 44.222,
        ])->assertSessionHasErrors(["name"]);
    }

    public function testCityCanBeDeleted(): void
    {
        $city = City::factory()->create();

        $this->delete(route(name:"cities.destroy", parameters: $city));

        $this->assertDatabaseMissing(table: "cities", data: $city->toArray());
    }
}
