<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class SignupTest extends TestCase
{
    public function testUserCanSignupWithProperCredentials(): void
    {
        $user = [
            "name" => "Test",
            "email" => "test@example.com",
            "password" => "123456789",
        ];

        $response = $this->postJson("/api/register", $user);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson(["message" => "User created."]);

        $this->assertDatabaseHas("users", ["email" => "test@example.com"]);
    }

    public function testUserCannotBeCreatedWithInvalidName(): void
    {
        $response = $this->postJson("/api/register", [
            "name" => Str::random(256),
            "email" => "email@example.com",
            "password" => "123456789",
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrorFor("name");
    }

    public function testUserCannotBeCreatedWithInvalidEmail(): void
    {
        $response = $this->postJson("/api/register", [
            "name" => "Test",
            "email" => "invalid-email",
            "password" => "123456789",
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrorFor("email");
    }

    public function testUserCannotBeCreatedWithInvalidPassword(): void
    {
        $response = $this->postJson("/api/register", [
            "name" => "Test",
            "email" => "email@example.com",
            "password" => "123",
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrorFor("password");
    }
}
