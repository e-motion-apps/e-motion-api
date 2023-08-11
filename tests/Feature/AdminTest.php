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

    public function testAdminCanEnterAdminDashboardCountries(): void
    {
        $this->get("/admin/countries");
        $this->assertAuthenticatedAs(User::first());
    }
}
