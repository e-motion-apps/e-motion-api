<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function testUserCanLoginWithValidCredentials(): void
    {
        User::factory()->create([
            "email" => "email@example.com",
            "password" => Hash::make("password@example"),
        ]);

        $response = $this->postJson("/api/login", [
            "email" => "email@example.com",
            "password" => "password@example",
        ]);

        $response->assertJsonStructure([
            "access_token",
        ]);
    }

    public function testAuthenticatedUserCanLogout(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson("/api/logout");

        $response->assertJson([
            "message" => "Logged out.",
        ]);
    }

    public function testUserCannotLogInWithInvalidCredentials(): void
    {
        User::query()->create([
            "name" => "Test",
            "email" => "email@example.com",
            "password" => Hash::make("123456789"),
        ]);
        $response = $this->postJson("/api/login", [
            "email" => "email@example.com",
            "password" => "password",
        ]);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(["message" => "Invalid credentials."]);

        $response = $this->postJson("/api/login", [
            "email" => "invalid@example.com",
            "password" => "123456789",
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(["message" => "Invalid credentials."]);
    }
}
