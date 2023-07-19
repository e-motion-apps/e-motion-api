<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\City;
use App\Models\Country;
use App\Models\User;
use Generator;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CityControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::create(["name" => "admin"]);
        $adminUser = User::factory()->create();
        $adminUser->assignRole($adminRole);
        $this->actingAs($adminUser);
    }

    public function testCitiesViewCanBeRendered(): void
    {
        City::factory()->count(3)->create();

        $this->get("/admin/cities")->assertInertia(
            fn(Assert $page) => $page
                ->component(value: "Cities/Index")
                ->has("cities", 3),
        );
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

        $this->post(uri:"/admin/cities", data: $city);

        $this->assertDatabaseHas(table:"cities", data: $city);
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

        $this->post(uri: "/admin/cities", data: $city);

        $this->assertDatabaseMissing(table: "cities", data: $city);
    }

    /**
     * @dataProvider invalidCityDataProvider
     */
    public function testCityCannotBeCreatedWithInvalidData(array $data, array $expectedErrors): void
    {
        $response = $this->post(uri: "/admin/cities", data: $data);

        $response->assertSessionHasErrors($expectedErrors);
    }

    /**
     * @dataProvider invalidCityDataProvider
     */
    public function testCityCannotBeUpdatedWithInvalidData(array $data, array $expectedErrors): void
    {
        $city = City::factory()->create();

        $response = $this->patch(uri: "/admin/cities/{$city->id}", data: $data);

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
        $country = Country::factory()->create();

        $data = [
            "name" => "Legnica",
            "latitude" => 44.543,
            "longitude" => -43.122,
            "country_id" => $country->id,
        ];

        $city = City::factory()->create();

        $this->patch(uri: "/admin/cities/{$city->id}", data: $data);

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

        $this->patch(uri: "/admin/cities/{$cityToUpdate->id}", data: [
            "name" => "Legnica",
            "latitude" => 32.444,
            "longitude" => 44.222,
            "country_id" => 1,
        ])->assertSessionHasErrors(["name"]);
    }

    public function testCityCanBeDeleted(): void
    {
        $city = City::factory()->create();

        $this->delete(uri:"/admin/cities/{$city->id}");

        $this->assertDatabaseMissing(table: "cities", data: $city->toArray());
    }
}
