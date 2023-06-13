<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Country;
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

    public function testCountryStoreIsRefusingInvalidData(): void
    {
        $response = $this->post(route(name: "countries.store"), data: [
            "name" => "Poland",
            "latitude" => 44.543,
            "longitude" => "12.345string",
            "iso" => "pl",
        ]);

        $response->assertSessionHasErrors();
    }

    public function testCountryUpdate(): void
    {
        $country = [
            "name" => "Poland",
            "latitude" => 44.543,
            "longitude" => -43.122,
            "iso" => "pl",
        ];

        $this->post(route(name: "countries.store"), data: $country);

        $this->assertDatabaseHas(table:"countries", data: $country);
    }

    public function testCountryUpdateIsRefusingInvalidData(): void
    {
        $this->testCountryUpdateProperties(name: "poland");
        $this->testCountryUpdateProperties(longitude: "string");
        $this->testCountryUpdateProperties(latitude: "string");
        $this->testCountryUpdateProperties(iso: "Pl");
    }

    public function testCountryDestroy(): void
    {
        $country = Country::factory()->create();

        $this->delete(route(name:"countries.destroy", parameters: $country));

        $this->assertDatabaseMissing(table: "countries", data: $country->toArray());
    }

    private function testCountryUpdateProperties($name = "Poland", $longitude = 33.22, $latitude = -23.24, $iso = "pl"): void
    {
        $country = Country::factory()->create();

        $invalidCountry = [
            "name" => $name,
            "longitude" => $longitude,
            "latitude" => $latitude,
            "iso" => $iso,
        ];

        $this->patch(route("countries.update", ["country" => $country]), $invalidCountry);
        $this->assertDatabaseMissing("countries", $invalidCountry);
    }
}
