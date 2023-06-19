<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class SignupTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testOfSignupDisplay(): void
    {
        $response = $this->get("/signup");

        $response->assertStatus(200);
    }

    public function testSuccesfullSignup(): void
    {
        $response = $this->post("/register", [
            "name" => "Test",
            "email" => "test@example.com",
            "password" => "123456789",
        ]);
        $response->assertStatus(302);
    }
}
