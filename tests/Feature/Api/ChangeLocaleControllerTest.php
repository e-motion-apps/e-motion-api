<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Tests\TestCase;

class ChangeLocaleControllerTest extends TestCase
{
    public function testLanguageChangeHappens(): void
    {
        $response = $this->postJson("/api/language/en");

        $response->assertOk()
            ->assertJson(["message" => "Language has been changed."]);

        $response = $this->postJson("/api/language/pl");

        $response->assertOk()
            ->assertJson(["message" => "Język został zmieniony."]);
    }
}
