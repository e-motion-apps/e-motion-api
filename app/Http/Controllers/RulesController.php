<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\Rules;
use App\Services\OpenAIService;
use Exception;

class RulesController
{
    public function getRules($country, $city): array
    {
        $city = ucfirst($city);
        $country = ucfirst($country);

        try {
            $country_id = Country::query()
                ->where("name", $country)
                ->orWhere("alternative_name", $country)
                ->first()->id;
            $city = City::query()
                ->where("name", $city)->where("country_id", $country_id)
                ->first();
        } catch (Exception $e) {
            return ["message" => "City not found"];
        }
        $rules = Rules::query()
            ->where("city_id", $city->id)
            ->first();

        if (!$rules || $rules->rules_en === null || $rules->rules_pl === null) {
            $cityData = [
                "city_id" => $city->id,
                "country_id" => $country_id,
                "city_name" => $city->name,
                "country_name" => $country,
            ];

            $importer = new OpenAIService();

            $data = $importer->importRulesForCity($cityData, true);
        } else {
            $data = [
                "country" => $country,
                "city" => $city->name,
                "rules_en" => $rules->rules_en,
                "rules_pl" => $rules->rules_pl,
            ];
        }

        return $data;
    }

    public function importRules(bool $force): void
    {
        try {
            $importer = new OpenAIService();
        } catch (Exception $e) {
            return;
        }
        $importer->importRulesForAllCities($force);
    }
}
