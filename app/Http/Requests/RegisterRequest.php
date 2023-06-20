<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "name" => ["required", "string", "max:60"],
            "email" => ["required", "string", "email", "max:255", "unique:users"],
            "password" => ["required", Password::defaults()],
        ];
    }
}
