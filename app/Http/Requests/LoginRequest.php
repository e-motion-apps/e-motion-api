<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "email" => ["required", "string", "email"],
            "password" => ["required", "string"],
        ];
    }
}
