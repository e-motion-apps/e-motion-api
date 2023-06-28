<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanLoginWithValidCredentials(): void
    {
        $admin = User::create([
            "name" => "Admin User",
            "email" => "admin@example.com",
            "password" => Hash::make("password@example"),
            "role" => "admin",
        ]);

        $response = $this->post("/login", [
            "email" => "admin@example.com",
            "password" => "password@example",
        ]);

        $response->assertStatus(302);
        $this->assertAuthenticatedAs($admin);
    }

    public function testAdminCanAccessDashboard(): void
    {
        $admin = User::create([
            "name" => "Admin User",
            "email" => "admin@example.com",
            "password" => Hash::make("password@example"),
            "role" => "admin",
        ]);

        $this->actingAs($admin);

        $response = $this->get("/dashboard");
        $response->assertStatus(200);
        $this->assertAuthenticatedAs($admin);
    }

    public function testAdminCanAccessCountry(): void
    {
        $admin = User::create([
            "name" => "adminUser",
            "email" => "admin@example.com",
            "password" => Hash::make("password@example"),
            "role" => "admin",
        ]);

        $this->actingAs($admin);

        $response = $this->get("/countries");
        $response->assertStatus(200);
        $this->assertAuthenticatedAs($admin);
    }

    public function testAdminCanEnterAdminPanel(): void
    {
        $admin = User::create([
            "name" => "adminUser123",
            "email" => "admin123@example.com",
            "password" => Hash::make("password@example"),
            "role" => "admin",
        ]);

        $this->actingAs($admin);

        $response = $this->get("/admin");
        $response->assertStatus(302);
        $this->assertAuthenticatedAs($admin);
    }
}
