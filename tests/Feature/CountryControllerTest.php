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
                ->component("Countries")
                ->has("countries.data", 3),
        );
    }

    public function testCountryStore(): void
    {
        $data = Country::factory()->make()->toArray();

        $this->post(route("countries.store"), $data);

        $this->assertDatabaseHas("countries", $data);
    }

    public function testCountryUpdate(): void
    {
        $data = Country::factory()->make()->toArray();

        $this->post(route("countries.store"), $data);

        $this->assertDatabaseHas("countries", $data);
    }

    public function testCountryDestroy(): void
    {
        $country = Country::factory()->create();

        $this->delete(route("countries.destroy", $country));

        $this->assertDatabaseMissing("countries", $country->toArray());
    }
}
