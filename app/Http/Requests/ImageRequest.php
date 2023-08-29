<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "image" => [
                "required",
                "dimensions:width=64,height=64",
                "max:40960",
            ],
        ];
    }
}
