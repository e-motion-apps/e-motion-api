<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\City;
use App\Models\Rules;
use OpenAI;

class OpenAIService
{
    public function importRules(bool $force): void
    {
        $data = [];
        $countriesKnownToHaveUniformRules = [
            "Poland", "Germany", "France", "Spain", "Italy", "Portugal", "Austria", "Czech Republic", "Slovakia", "Hungary", "Romania", "Bulgaria", "Greece", "Sweden", "Finland",
            "Norway", "Denmark", "Netherlands", "Belgium", "Switzerland", "Slovenia", "Croatia", "Serbia", "Bosnia and Herzegovina", "Montenegro", "Albania", "North Macedonia",
            "Kosovo", "Ukraine", "Belarus", "Lithuania", "Latvia", "Estonia", "Russia", "Ireland", "United Kingdom", "Turkey",
            "Cyprus", "Malta", "Iceland", "Luxembourg", "Moldova", "Liechtenstein", "Andorra", "Monaco", "San Marino", "Vatican City",
        ];

        $client = OpenAI::client(env("OPENAI_API_KEY"));
        $cities = City::query()->whereHas("cityProviders")->get();

        foreach ($cities as $city) {
            $data[] = ["city_id" => $city->id, "country_id" => $city->country->id, "city" => $city->name, "country" => $city->country->name,  "rulesENG" => null, "rulesPL" => null];
        }

        foreach ($data as $i) {
            $promptENG = "Act as helpful assistant. explain what are the legal limitations for riding electric scooters in " . $i["city"] . ", " . $i["country"] . "? Contain information about: max speed, helmet requirements, allowed ABV, passengers, other relevant details. Be formal, speak english. Don't include city name in your response. If you don't have information answering the question, write 'null'";
            $promptPL = "Zachowuj się jako pomocny asystant, wyjaśnij jakie są prawa dotyczące jazdy na hulajnogach elektrycznych w " . $i["city"] . ", " . $i["country"] . "? Zawrzyj informacje o: maksymalnej prędkości, potrzebie kasku, dozwolonym alkoholu we krwi, pasażerach, inne. Bądź formalny, mów po polsku. Nie zawieraj nazwy miasta w odpowiedzi. Jeśli nie masz informacji odpowiadających na pytanie, napisz 'null'";

            if (!$force) {
                $currentRules = Rules::query()->where("city_id", $i["city_id"])->where("country_id", $i["country_id"])->first();

                if ($currentRules->rulesENG !== null && $currentRules->rulesPL !== null) {
                    continue;
                }
            }

            $currentRulesInCountry = Rules::query()->where("country_id", $i["country_id"])->first();

            if (in_array($i["country"], $countriesKnownToHaveUniformRules, true) && $currentRulesInCountry !== null && $currentRulesInCountry->rulesENG !== null && $currentRulesInCountry->rulesPL !== null && $force === false) {
                $i["rulesENG"] = $currentRulesInCountry->rulesENG;
                $i["rulesPL"] = $currentRulesInCountry->rulesPL;
            } else {
                $responseENG = $client->chat()->create([
                    "model" => "gpt-3.5-turbo",
                    "messages" => [
                        [
                            "role" => "user",
                            "content" => $promptENG,
                        ],
                    ],
                ]);
                $responsePL = $client->chat()->create([
                    "model" => "gpt-3.5-turbo",
                    "messages" => [
                        [
                            "role" => "user",
                            "content" => $promptPL,
                        ],
                    ],
                ]);
                $i["rulesENG"] = $responseENG["choices"][0]["message"]["content"];
                $i["rulesPL"] = $responsePL["choices"][0]["message"]["content"];
            }

            if (strlen($i["rulesENG"]) < 700 || strlen($i["rulesPL"]) < 700) {
                continue;
            }

            Rules::query()->updateOrCreate([
                "city_id" => $i["city_id"],
                "country_id" => $i["country_id"],
                "rulesENG" => $i["rulesENG"],
                "rulesPL" => $i["rulesPL"],
            ]);
        }
    }
}
