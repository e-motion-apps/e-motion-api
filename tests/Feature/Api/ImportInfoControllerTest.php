<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ImportInfoControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $adminUser = User::factory()->create();
        Sanctum::actingAs($adminUser, ["HasAdminRole"]);
    }

    public function testImportInfoArrayIsReturned(): void
    {
        $response = $this->getJson("/api/admin/importers");

        $response->assertOk()
            ->assertJsonStructure([
                "importInfo",
                "codes",
                "providers",
            ]);
    }

    public function testUnauthorisedUserCannotAccessImportInfo(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $response = $this->getJson("/api/admin/importers");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
