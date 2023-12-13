<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use App\Mail\SuggestionFormMail;

class SuggestionFormMailTest extends TestCase
{
    public function testSuggestionCanBeQueued(): void
    {
        $this->withoutExceptionHandling();

        Mail::fake();

        $data = [
            "name" => fake()->name,
            "email" => fake()->safeEmail,
            "suggestion" => fake()->sentence,
        ];

        $response = $this->post("/submitSuggestion", $data);

        $response->assertStatus(200);

        Mail::assertQueued(SuggestionFormMail::class, function ($mail) use ($data) {
            return $mail->data["name"] === $data["name"]
                && $mail->data["email"] === $data["email"]
                && $mail->data["suggestion"] === $data["suggestion"]
                && $mail->hasTo(env("MAIL_ADDRESS_FOR_SUGGESTIONS"));
    });


    }
}
