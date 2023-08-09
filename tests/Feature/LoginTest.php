<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function testUserCanLoginWithValidCredentials(): void
    {
        $user = User::factory()->create([
            "email" => "email@example.com",
            "password" => bcrypt("password@example"),
        ]);

        $this->post("/login", [
            "email" => "email@example.com",
            "password" => "password@example",
        ]);

        $this->assertAuthenticatedAs($user);
    }

    public function testAuthenticatedUserCanLogout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $this->post("/logout");
        $this->assertGuest();
    }

    public function testUserCannotLoginWithInvalidPassword(): void
    {
        User::factory()->create([
            "email" => "email@example.com",
            "password" => bcrypt("password@example"),
        ]);

        $this->post("login", [
            "email" => "email@example.com",
            "password" => "IncorrectPassword",
        ]);

        $this->assertGuest();
    }
}
