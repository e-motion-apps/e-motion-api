<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class UpdateCountryRequest extends CountryRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge_recursive(
            parent::rules(),
            [
                "iso" => [$this->uniqueRuleForCountry("iso")],
                "name" => [$this->uniqueRuleForCountry("name")],
            ],
        );
    }

    protected function uniqueRuleForCountry(string $column): Unique
    {
        $currentCountryId = $this->route(param: "country");

        return Rule::unique(table: "countries", column: $column)->ignore($currentCountryId);
    }
}
