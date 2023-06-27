<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testShowLoginPage(): void
    {
        $response = $this->get("/login");

        $response->assertStatus(200);
    }

    public function testUserCanLoginWithValidCredentials(): void
    {
        $user = User::factory()->create([
            "email" => "email@example.com",
            "password" => bcrypt("password@example"),
        ]);

        $response = $this->post("/login", [
            "email" => "email@example.com",
            "password" => "password@example",
        ]);

        $response->assertStatus(302);
        $this->assertAuthenticatedAs($user);
    }

    public function testAuthenticatedUserCanLogout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post("/logout");
        $response->assertStatus(302);
        $this->assertGuest();
    }

    public function testUserCannotLoginWithInvalidPassword(): Void
    {
        $user = User::factory()->create([
            "email" => "email@example.com",
            "password" => bcrypt("password@example"),
        ]);

        $response = $this->post("login", [
            "email" => "email@example.com",
            "password" => "IncorrectPassword",
        ]);

        $response->assertStatus(302);
        $this->assertGuest();
    }
}
