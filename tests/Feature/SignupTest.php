<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Str;
use Tests\TestCase;

class SignupTest extends TestCase
{
    public function testUserCanSignupWithProperCredentials(): void
    {
        $user = [
            "name" => "Test",
            "email" => "test@example.com",
            "password" => "123456789",
            "password_confirmation" => "123456789",
        ];

        $this->post(uri: "/register", data: $user);

        $this->assertDatabaseHas(table: "users", data: ["email" => "test@example.com"]);
    }

    public function testUserCannotBeCreatedWithInvalidName(): void
    {
        $response = $this->post("/register", [
            "name" => Str::random(256),
            "email" => "email@example.com",
            "password" => "123456789",
            "password_confirmation" => "123456789",
        ]);

        $response->assertSessionHasErrors(["name"]);
    }
}
