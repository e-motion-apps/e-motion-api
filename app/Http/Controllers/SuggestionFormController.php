<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\SuggestionFormMail;
class SuggestionFormController extends Controller
{
    public function sendSuggestionEmail(){
        $data = request()->validate([
            'name' => 'required',
            'email' => 'required|email',
            'suggestion' => 'required',
        ]);

        Mail::to(env('MAIL_ADDRESS_FOR_SUGGESTIONS', "support@example.com"))->queue(new SuggestionFormMail($data));
    }
}
