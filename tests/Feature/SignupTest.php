<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Str;
use Tests\TestCase;

class SignupTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testShowSignupPage(): void
    {
        $response = $this->get("/signup");

        $response->assertStatus(200);
    }

    public function testUserCanSignupWithProperCredentials(): void
    {
        $response = $this->post("/register", [
            "name" => "Test",
            "email" => "test@example.com",
            "password" => "123456789",
        ]);
        $response->assertStatus(302);
    }

    public function testUserCannotBeCreatedWithInvalidName(): Void
    {
        $response = $this->post("/register", [
            "name" => Str::random(256),
            "email" => "email@example.com",
            "password" => bcrypt("password@example"),
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(["name"]);
    }

    public function testGuestCannotEnterDashboardPage(): void
    {
        $response = $this->get("/dashboard");

        $response->assertStatus(302);
        $response->assertRedirect("/login");
        $this->assertGuest();
    }

    public function testNewlySignedUpUserIsAuthenticatedAndCanEnterDashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get("/dashboard");

        $response->assertStatus(200);
        $this->assertAuthenticated();
    }
}
