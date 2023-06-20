<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
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
            "name" => "phasellus faucibus scelerisque eleifend donec pretium vulputate sapien nec sagittis aliquam malesuada bibendum arcu vitae elementum curabitur vitae nunc sed velit dignissim sodales ut eu sem integer vitae justo eget magna fermentum iaculis eu non diam phasellus vestibulum lorem sed risus ultricies tristique nulla aliquet enim tortor at auctor urna nunc id cursus metus aliquam eleifend mi in nulla posuere sollicitudin",
            "email" => "email@example.com",
            "password" => bcrypt("password@example"),
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseMissing("users", [
            "email" => "email@example.com",
        ]);
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

        $this->actingAs($user)
                 ->get("/dashboard");
                 ->assertStatus(200);
                 ->assertAuthenticated();
    }
}
