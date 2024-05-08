<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class SocialMediaLoginTest extends TestCase
{
    public function testGetProviderRedirectUrl(): void
    {
        $response = $this->getJson(route("api.login.provider", ["provider" => "github"]));

        $response->assertOk()
            ->assertJsonStructure([
                "redirect_url",
            ]);
    }

    public function testUserIsLoggedIn(): void
    {
        Socialite::shouldReceive("driver->user")->andReturn(Mockery::mock([
            "getId" => 1,
            "getEmail" => "test@example.com",
            "getName" => "Test",
        ]));

        $response = $this->getJson(route("api.login.provider.redirect", ["provider" => "github"]));
        $response->assertOk()
            ->assertJsonStructure([
                "access_token",
                "userId",
            ]);
    }
}
