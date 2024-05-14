<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\Rules;
use App\Services\OpenAIService;
use Illuminate\Http\Request;

class RulesController
{
    public function getRules(Country $country, City $city): array
    {
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
                "country" => $country->name,
                "city" => $city->name,
                "rules_en" => $rules->rules_en,
                "rules_pl" => $rules->rules_pl,
            ];
        }

        return $data;
    }

    public function importRules(Request $request, OpenAIService $importer): void
    {
        $force = $request->input("force", false);
        $importer->importRulesForAllCities($force);
    }
}
