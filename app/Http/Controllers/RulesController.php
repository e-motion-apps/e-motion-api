<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\Rules;

class RulesController
{
    public function getRules($country, $city): array
    {
        $country_id = Country::query()
            ->where("name", $country)
            ->first()->id;
        $city = City::query()
            ->where("name", $city)->where("country_id", $country_id)
            ->first();
        $rules = Rules::query()
            ->where("city_id", $city->id)
            ->first();

        $data = [
            "country" => $country,
            "city" => $city->name,
            "rulesENG" => $rules->rulesENG,
            "rulesPL" => $rules->rulesPL,
        ];

        return $data;
    }
}
