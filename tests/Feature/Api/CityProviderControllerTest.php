<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\City;
use App\Models\Provider;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CityProviderControllerTest extends TestCase
{
    public function testCityProviderDataIsReturned(): void
    {
        $response = $this->getJson("/api/providers");

        $response->assertOk()
            ->assertJsonStructure([
                "cities",
                "providers",
                "countries",
            ]);
    }

    public function testCityProviderDataIsUpdated(): void
    {
        $adminUser = User::factory()->create();
        Sanctum::actingAs($adminUser, ["HasAdminRole"]);
        Provider::query()->create([
            "name" => "provider1",
            "url" => "http://provider1.com",
            "color" => "#000000",
        ]);
        $city = City::factory()->create();

        $response = $this->patchJson("/api/update-city-providers/$city->id", [
            "providerNames" => ["provider1"],
            "city" => $city,
        ]);

        $response->assertOk()
            ->assertJson([
                "message" => "City providers updated successfully.",
            ]);
        $this->assertDatabaseHas("city_providers", [
            "city_id" => $city->id,
            "provider_name" => "provider1",
            "created_by" => "admin",
        ]);
    }
}
