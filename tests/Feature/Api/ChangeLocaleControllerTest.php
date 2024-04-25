<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ChangeLocaleControllerTest extends TestCase
{
    public function testUserCanChangeLanguageToSupportedLanguage(): void
    {
        $response = $this->postJson("/api/language/en");

        $response->assertOk()
            ->assertJson(["message" => "Language has been changed."]);

        $response = $this->postJson("/api/language/pl");

        $response->assertOk()
            ->assertJson(["message" => "Język został zmieniony."]);
    }

    public function testUserCannotChangeLanguageToUnsupportedLanguage(): void
    {
        $response = $this->postJson("/api/language/unsupported");

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(["message" => "Error changing the language."]);

        $this->postJson("/api/language/pl");

        $response = $this->postJson("/api/language/unsupported");
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(["message" => "Błąd podczas zmiany języka."]);
    }
}
