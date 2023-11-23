<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityOpinionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "rating" => ["required", "numeric", "min:1", "max:5"],
            "content" => ["required", "string", "max:250"],
            "city_id" => ["required", "numeric", "exists:cities,id"],
        ];
    }
}
