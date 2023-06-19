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
    public function testOfLoginDisplay(): void
    {
        $response = $this->get("/login");

        $response->assertStatus(200);
    }

    public function testOfLogin(): void
    {
        $response = $this->post("/login", [
            "email" => "Email@example.com",
            "password" => "Password@example",
        ]);
        $response->assertStatus(302);
    }

    public function testLogout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post("/logout");
        $response->assertStatus(302);
    }
}
