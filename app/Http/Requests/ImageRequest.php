<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class ImageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "image" => [
                "required",
                "mimes:png",
                File::image()
                    ->max(40 * 1024)
                    ->dimensions(Rule::dimensions()->width(64)->height(64)),
            ],
        ];
    }
}
