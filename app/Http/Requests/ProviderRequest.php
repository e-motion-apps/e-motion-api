<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProviderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "providers" => [],
        ];
    }
}
