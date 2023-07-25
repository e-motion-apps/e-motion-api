<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\City;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoritesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCityCanBeAddedToFavorites(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $city = City::factory()->create();

        $response = $this->post("/favorites", ["city_id" => $city->id]);

        $response->assertStatus(200);

        $this->assertDatabaseHas("favorites", [
            "user_id" => $user->id,
            "city_id" => $city->id,
        ]);
    }

    public function testCityCanBeRemovedFromFavorites(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $city = City::factory()->create();

        $this->post("/favorites", ["city_id" => $city->id]);

        $response = $this->post("/favorites", ["city_id" => $city->id]);

        $response->assertStatus(200);

        $this->assertDatabaseMissing("favorites", [
            "user_id" => $user->id,
            "city_id" => $city->id,
        ]);
    }
}
