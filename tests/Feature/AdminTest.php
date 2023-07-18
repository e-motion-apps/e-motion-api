<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::create(["name" => "admin"]);
        $adminUser = User::factory()->create([
            "name" => "Admin User",
            "email" => "admin@example.com",
            "password" => Hash::make("password@example"),
        ]);

        $adminUser->assignRole($adminRole);

        $this->actingAs($adminUser);
    }

    public function testAdminCanLoginWithValidCredentials(): void
    {
        $response = $this->post("/login", [
            "email" => "admin@example.com",
            "password" => "password@example",
        ]);

        $response->assertRedirect("/");
    }

    public function testAdminCanEnterAdminPage(): void
    {
        $response = $this->get("/admin");
        $response->assertStatus(200);
        $this->assertAuthenticatedAs(User::first());
    }

    public function testAdminCanEnterAdminDashboardCountries(): void
    {
        $response = $this->get("/admin/dashboard/countries");
        $response->assertStatus(200);
        $this->assertAuthenticatedAs(User::first());
    }
}
