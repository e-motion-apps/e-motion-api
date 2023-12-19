<?php

declare(strict_types=1);

namespace Tests\Feature;

use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class SocialMediaLoginTest extends TestCase
{
    public function testRedirectToProvider(): void
    {
        $response = $this->get(route("login.provider", ["provider" => "github"]));

        $response->assertStatus(302);
    }

    public function testProviderCallbackIsHandled(): void
    {
        $response = $this->get(route("login.provider.redirect", ["provider" => "github"]));

        $response->assertStatus(302);
    }

    public function testUserIsLoggedIn(): void
    {
        Socialite::shouldReceive("driver->user")->andReturn(Mockery::mock([
            "getId" => 1,
            "getEmail" => "test@example.com",
            "getName" => "Test",
        ]));

        $this->get(route("login.provider", ["provider" => "github"]));
        $this->get(route("login.provider.redirect", ["provider" => "github"]));
        $this->assertAuthenticated();
    }
}

