<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
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

    public function testUserCannotLoginWithInvalidPassword(): void
    {
        User::factory()->create([
            "email" => "email@example.com",
            "password" => Hash::make("password@example"),
        ]);

        $response = $this->postJson("/api/login", [
            "email" => "email@example.com",
            "password" => "IncorrectPassword",
        ]);

        $response->assertJson([
            "message" => "Invalid credentials.",
        ]);
    }
}
