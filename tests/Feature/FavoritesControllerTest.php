<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\City;
use App\Models\User;
use Tests\TestCase;

class FavoritesControllerTest extends TestCase
{
    public function testCityCanBeAddedToFavorites(): void
    {
        $user = User::factory()->create();
        $city = City::factory()->create();

        $this->actingAs($user)
            ->post("/favorites", ["city_id" => $city->id])
            ->assertStatus(200);
    }

    public function testCityCanBeRemovedFromFavorites(): void
    {
        $user = User::factory()->create();
        $city = City::factory()->create();

        $this->actingAs($user)
            ->post("/favorites", ["city_id" => $city->id]);

        $this->actingAs($user)
            ->post("/favorites", ["city_id" => $city->id])
            ->assertStatus(200);

        $this->assertDatabaseMissing("favorites", [
            "user_id" => $user->id,
            "city_id" => $city->id,
        ]);
    }
}
