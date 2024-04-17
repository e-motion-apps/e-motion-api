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
    public function getRules(string $countryName, string $cityName): array
    {
        $countryName = ucfirst($countryName);
        $cityName = ucfirst($cityName);

        try {
            $country = Country::query()
                ->where("name", $countryName)
                ->orWhere("alternative_name", $countryName)
                ->first();
            $city = City::query()
                ->where("name", $cityName)
                ->where("country_id", $country->id)
                ->first();
        } catch (Exception $e) {
            return ["message" => __("City not found")];
        }

        $rules = Rules::query()
            ->where("city_id", $city->id)
            ->first();

        if (!$rules || $rules->rules_en === null || $rules->rules_pl === null) {
            $cityData = [
                "cityId" => $city->id,
                "countryId" => $country->id,
                "cityName" => $city->name,
                "countryName" => $country->name,
            ];
            $importer = new OpenAIService();
            $data = $importer->importRulesForCity($cityData, true);
        } else {
            $data = [
                "country" => $countryName,
                "city" => $city->name,
                "rules_en" => $rules->rules_en,
                "rules_pl" => $rules->rules_pl,
            ];
        }

        return $data;
    }

    public function importRules(bool $force, OpenAIService $importer): void
    {
        $importer->importRulesForAllCities($force);
    }
}
