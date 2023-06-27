<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanLoginWithValidCredentials(): void
    {
        $admin = User::factory()->create([
            "email" => "admin@example.com",
            "password" => bcrypt("password@example"),
        ]);

        $response = $this->post("/login", [
            "email" => "admin@example.com",
            "password" => "password@example",
        ]);

        $response->assertStatus(302);
        $this->assertAuthenticatedAs($admin);
    }
}
