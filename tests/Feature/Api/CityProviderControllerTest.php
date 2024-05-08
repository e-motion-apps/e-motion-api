<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\City;
use App\Models\Provider;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CityProviderControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $adminUser = User::factory()->create();
        Sanctum::actingAs($adminUser, ["HasAdminRole"]);
    }

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
        Provider::query()->create([
            "name" => "provider1",
            "url" => "https://provider1.com",
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

    public function testCityProviderCannotBeUpdatedWithInvalidData(): void
    {
        $city = City::factory()->create();
        $response = $this->patchJson("/api/update-city-providers/$city->id", [
            "providerNames" => ["provider1"],
            "city" => $city,
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                "message" => "Provider does not exist.",
            ]);
    }

    public function testCityProviderCannotBeUpdatedByUnauthorisedUser(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $city = City::factory()->create();
        $provider = Provider::query()->create([
            "name" => "provider1",
            "url" => "https://provider1.com",
            "color" => "#000000",
        ]);
        $response = $this->patchJson("/api/update-city-providers/$city->id", [
            "providerNames" => [$provider->name],
            "city" => $city,
        ]);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
