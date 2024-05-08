<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class ApiProviderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "name" => ["required", "string", "regex:/^[A-Z\s]/", "max:100", $this->uniqueRuleForProvider("name")],
            "color" => ["required", "string", "size:7"],
            "url" => ["nullable", "url"],
            "android_url" => ["nullable", "url"],
            "ios_url" => ["nullable", "url"],
            "file" => [
                "required",
                "string",
                "regex:/^data:image\/(png);base64,[a-zA-Z0-9+\/]+=*$/",
            ],
        ];
    }

    protected function uniqueRuleForProvider(string $column): Unique
    {
        $currentProviderId = $this->route(param: "provider");

        return Rule::unique(table: "providers", column: $column)->ignore($currentProviderId);
    }
}
