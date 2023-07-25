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

    public function testCityCanBeAddedToAndRemovedFromFavorites(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $city = City::factory()->create();
        $response = $this->post("/favorites", ["city_id" => $city->id]);

        $response->assertSessionHas("message", "City added to favorites!");

        $this->assertTrue($this->app->make('App\Http\Controllers\FavoritesController')->check($city->id));

        $response = $this->post("/favorites", ["city_id" => $city->id]);

        $response->assertSessionHas("message", "City removed from favorites!");

        $this->assertFalse($this->app->make('App\Http\Controllers\FavoritesController')->check($city->id));
    }
}
