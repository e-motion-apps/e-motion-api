<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\CityAlternativeName;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CityAlternativeNameController extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $adminUser = User::factory()->create();
        Sanctum::actingAs($adminUser, ["HasAdminRole"]);
    }

    public function testCityAlternativeNameCanBeStored(): void
    {
        $response = $this->postJson(route("city-alternative-names.store"), [
            "city_id" => 1,
            "name" => "Test City Alternative Name",
        ]);

        $response->assertCreated()
            ->assertJson(["message" => __("City alternative name created successfully.")]);
    }

    public function testCityAlternativeNameCanBeDeleted(): void
    {
        $cityAlternativeName = CityAlternativeName::factory()->create();

        $response = $this->deleteJson(route("city-alternative-names.destroy", $cityAlternativeName->id));

        $response->assertOk()
            ->assertJson(["message" => __("City alternative name deleted successfully.")]);
    }

    public function testCityAlternativeNamesDuplicatesAreNotCreated(): void
    {
        $cityAlternativeName = CityAlternativeName::factory()->create();
        $response = $this->postJson(route("city-alternative-names.store"), [
            "city_id" => $cityAlternativeName->city_id,
            "name" => $cityAlternativeName->name,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(["name"]);
    }

    public function testNonAdminUserCannotAddAlternativeNames(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson(route("city-alternative-names.store"), [
            "city_id" => 1,
            "name" => "Test City Alternative Name",
        ]);
        $response->assertStatus(Response::HTTP_FORBIDDEN);

    }

    public function testNonAdminUserCannotDeleteAlternativeNames()
    {
        Sanctum::actingAs(User::factory()->create());
        $cityAlternativeName = CityAlternativeName::factory()->create();

        $response = $this->deleteJson(route("city-alternative-names.destroy", $cityAlternativeName->id));
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
