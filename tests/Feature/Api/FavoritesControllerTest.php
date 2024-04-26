<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\City;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class FavoritesControllerTest extends TestCase
{
    public function testCityCanBeAddedToFavorites(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $city = City::factory()->create();

        $this->postJson("/api/favorites/", ["city_id" => $city->id]);

        $this->assertDatabaseHas("favorites", ["city_id" => $city->id]);
    }

    public function testCityCanBeRemovedFromFavorites(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $city = City::factory()->create();

        $this->postJson("/api/favorites/", ["city_id" => $city->id]);

        $this->assertDatabaseHas("favorites", [
            "user_id" => $user->id,
            "city_id" => $city->id,
        ]);

        $this->postJson("/api/favorites", ["city_id" => $city->id]);

        $this->assertDatabaseMissing("favorites", [
            "user_id" => $user->id,
            "city_id" => $city->id,
        ]);
    }

    public function testUnauthenticatedUserCannotAddCityToFavourites(): void
    {
        $city = City::factory()->create();

        $response = $this->postJson("/api/favorites/", ["city_id" => $city->id]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testNonExistentCityCannotBeAddedToFavourites(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/favorites/", ["city_id" => 999]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testRequestWithoutCityIdReturnsBadRequest(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/favorites/");

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testUnauthenticatedUserCannotCheckFavourites(): void
    {
        $response = $this->getJson("/api/favorite-cities");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
