<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\City;
use App\Models\CityOpinion;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CityOpinionControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    }

    public function testCityOpinionCanBeCreated(): void
    {
        $city = City::factory()->create();
        $response = $this->postJson("/api/opinions", [
            "rating" => 5,
            "content" => "Great city!",
            "city_id" => $city->id,
        ]);
        $response->assertCreated()
            ->assertJson([
                "message" => "Opinion added successfully.",
            ]);
        $this->assertDatabaseHas("city_opinions", [
            "rating" => 5,
            "content" => "Great city!",
            "city_id" => $city->id,
        ]);
    }

    public function testCityOpinionCanBeUpdated(): void
    {
        $city = City::factory()->create();
        $response = $this->postJson("/api/opinions", [
            "rating" => 5,
            "content" => "Great city!",
            "city_id" => $city->id,
        ]);
        $response->assertCreated()
            ->assertJson([
                "message" => "Opinion added successfully.",
            ]);
        $opinion_id = CityOpinion::query()->first()->id;
        $response = $this->patchJson("/api/opinions/$opinion_id", [
            "rating" => 4,
            "content" => "Good city!",
            "city_id" => $city->id,
        ]);

        $response->assertJson([
            "message" => "Opinion edited successfully.",
        ]);
    }

    public function testCityOpinionCanBeDeleted(): void
    {
        $city = City::factory()->create();
        $response = $this->postJson("/api/opinions", [
            "rating" => 5,
            "content" => "Great city!",
            "city_id" => $city->id,
        ]);
        $response->assertCreated()
            ->assertJson([
                "message" => "Opinion added successfully.",
            ]);
        $opinion_id = CityOpinion::query()->first()->id;
        $response = $this->deleteJson("/api/opinions/$opinion_id");
        $response->assertOk()
            ->assertJson([
                "message" => "Opinion removed successfully!",
            ]);
    }

    public function testUnauthorizedUserCannotEditOpinion(): void
    {
        $city = City::factory()->create();
        $response = $this->postJson("/api/opinions", [
            "rating" => 5,
            "content" => "Great city!",
            "city_id" => $city->id,
        ]);
        $response->assertCreated()
            ->assertJson([
                "message" => "Opinion added successfully.",
            ]);

        Sanctum::actingAs(User::factory()->create());

        $opinion_id = CityOpinion::query()->first()->id;
        $response = $this->patchJson("/api/opinions/$opinion_id", [
            "rating" => 4,
            "content" => "Good city!",
            "city_id" => $city->id,
        ]);

        $response->assertNotFound();
    }

    public function testUnauthorizedUserCannotDeleteOpinion(): void
    {
        $city = City::factory()->create();
        $response = $this->postJson("/api/opinions", [
            "rating" => 5,
            "content" => "Great city!",
            "city_id" => $city->id,
        ]);
        $response->assertCreated()
            ->assertJson([
                "message" => "Opinion added successfully.",
            ]);

        Sanctum::actingAs(User::factory()->create());

        $opinion_id = CityOpinion::query()->first()->id;

        $response = $this->deleteJson("/api/opinions/$opinion_id");
        $response->assertNotFound();
    }

    public function testUserCannotAddOpinionForNonExistentCity(): void
    {
        $response = $this->postJson("/api/opinions", [
            "rating" => 5,
            "content" => "Great city!",
            "city_id" => 999,
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
