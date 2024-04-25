<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::factory()->create([
            "name" => "Admin User",
            "email" => "admin@example.com",
            "password" => Hash::make("password@example"),
        ]);
    }

    public function testAdminCanLoginWithValidCredentials(): void
    {
        $response = $this->postJson("/api/login", [
            "email" => "admin@example.com",
            "password" => "password@example",
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                "abilities",
                "access_token",
            ]);
    }

    public function testAdminCanEnterAdminDashboardCountries(): void
    {
        Sanctum::actingAs($this->adminUser, ["HasAdminRole"]);

        $response = $this->getJson("/api/admin/countries");
        $response->assertOk()
            ->assertJsonStructure([
                "countries" => [
                    "*" => [
                        "id",
                        "name",
                        "created_at",
                        "updated_at",
                    ],
                ],
            ]);
        $this->assertAuthenticatedAs(User::first());
    }

    public function testUserCannotAccessAdminRoutes(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson("/api/admin/countries");
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
