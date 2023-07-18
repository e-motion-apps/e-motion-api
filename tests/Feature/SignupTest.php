<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Str;
use Tests\TestCase;

class SignupTest extends TestCase
{
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
}
