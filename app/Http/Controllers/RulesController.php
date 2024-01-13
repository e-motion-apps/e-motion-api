<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\Rules;
use App\Services\OpenAIService;

class RulesController
{
    public function getRules($country, $city): array
    {
        $country_id = Country::query()
            ->where("name", $country)
            ->first()->id;
        if(!$country_id){
            $country_id = Country::query()
                ->where("alternative_name", $country)
                ->first()->id;
        }
        $city = City::query()
            ->where("name", $city)->where("country_id", $country_id)
            ->first();
        $rules = Rules::query()
            ->where("city_id", $city->id)
            ->first();

        if(!$rules || !$rules->rulesENG || !$rules->rulesPL){
            $cityData = [
                "city_id" => $city->id,
                "country_id" => $country_id,
                "city_name" => $city->name,
                "country_name" => $country,
            ];
            $importer = new OpenAIService();
             $data = $importer->importRulesForCity($cityData, true);
        }else{
            $data = [
                "country" => $country,
                "city" => $city->name,
                "rulesENG" => $rules->rulesENG,
                "rulesPL" => $rules->rulesPL,
            ];
        }



        return $data;
    }
}
