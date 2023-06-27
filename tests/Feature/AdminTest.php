<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;


class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanAccessDashboard(): void
    {
        $this->seed(); 
        
        $admin = User::whereHas("roles", function ($query) {
            $query->where("name", "admin");
        })->first();

        $response = $this->actingAs($admin)->get("/dashboard");

        $response->assertStatus(200);
    }
    public function testAdminCanAccessCountry(): void
    {
        $this->seed();
        
        $admin = User::whereHas("roles", function ($query) {
            $query->where("name", "admin");
        })->first();

        $response = $this->actingAs($admin)->get("/countries");

        $response->assertStatus(200);
    }
}

