<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\SuggestionFormMail;
use Illuminate\Support\Facades\Mail;

class SuggestionFormController extends Controller
{
    public function sendSuggestionEmail(): void
    {
        $data = request()->validate([
            "name" => "required",
            "email" => "required|email",
            "suggestion" => "required",
        ]);

        Mail::to(env("MAIL_ADDRESS_FOR_SUGGESTIONS", "support@example.com"))->queue(new SuggestionFormMail($data));
    }
}
