<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\City;
use App\Models\Country;
use Tests\TestCase;

class CityPageControllerTest extends TestCase
{
    public function testCityPageInfoArrayIsReturned(): void
    {
        $country = Country::factory()->create();
        $city = City::factory()->create([
            "country_id" => $country->id,
        ]);

        $response = $this->getJson("/api/$country->slug/$city->slug");
        $response->assertOk()
            ->assertJsonStructure([
                "city",
                "providers",
                "cityOpinions",
            ]);
    }
}
