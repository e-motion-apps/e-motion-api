<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\CityAlternativeName;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
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
}
