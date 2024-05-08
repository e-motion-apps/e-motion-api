<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProviderControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $adminUser = User::factory()->create();
        Sanctum::actingAs($adminUser, ["HasAdminRole"]);
    }

    public function testProvidersArrayIsReturned(): void
    {
        $response = $this->getJson("/api/admin/providers");

        $response->assertOk()
            ->assertJsonStructure([
                "providers" => ["*" => []],
            ]);
    }

    public function testProviderCanBeCreated(): void
    {
        Storage::fake("public");
        $logo = Image::canvas(150, 100, "#000");
        $base64Logo = (string)$logo->encode("data-url");
        $provider = [
            "name" => "Provider",
            "url" => "https://example.com",
            "color" => "#000000",
            "file" => $base64Logo,
        ];

        $response = $this->postJson("/api/admin/providers", $provider);

        $response->assertCreated();
        unset($provider["file"]);
        $this->assertDatabaseHas("providers", $provider);
    }

    public function testProviderCanBeUpdated(): void
    {
        Storage::fake("public");
        $logo = Image::canvas(150, 100, "#000");
        $base64Logo = (string)$logo->encode("data-url");
        $provider = [
            "name" => "Provider",
            "url" => "https://example.com",
            "color" => "#000000",
            "file" => $base64Logo,
        ];

        $this->postJson("/api/admin/providers", $provider);

        unset($provider["file"]);
        $this->assertDatabaseHas("providers", $provider);

        $updatedProvider = [
            "name" => "Provider",
            "color" => "#FFFFFF",
            "url" => "https://example.com",
            "file" => $base64Logo,
        ];

        $providerName = $provider["name"];
        $response = $this->putJson("/api/admin/providers/$providerName", $updatedProvider);
        $response->assertOk();

        unset($updatedProvider["file"]);
        $this->assertDatabaseHas("providers", $updatedProvider);
    }

    public function testUnauthorisedUserCannotAccessProviders(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $response = $this->getJson("/api/admin/providers");
        $response->assertNotFound();
    }
}
