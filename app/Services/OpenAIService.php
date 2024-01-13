<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\City;
use App\Models\Rules;
use Illuminate\Contracts\Queue\ShouldQueue;
use OpenAI;
use App\Jobs\ImportCityRulesJob;

class OpenAIService implements ShouldQueue
{
    private OpenAI\Client $client;

    public function __construct()
    {
        $this->client = OpenAI::client(env("OPENAI_API_KEY"));
    }

    public function importRulesForAllCities(bool $force): void
    {
        $cities = City::query()->whereHas('cityProviders')->orderBy('country.name')->get();
        foreach ($cities as $city) {
            $cityData = [
                "city_id" => $city->id,
                "country_id" => $city->country_id,
                "city_name" => $city->name,
                "country_name" => $city->country->name,
            ];
            ImportCityRulesJob::dispatch($cityData, $force);
        }
    }

    private function askGPT($prompt): string
    {
        $response = $this->client->chat()->create([
            "model" => "gpt-3.5-turbo",
            "messages" => [
                [
                    "role" => "user",
                    "content" => $prompt,
                ],
            ],
        ]);

        return $response["choices"][0]["message"]["content"];
    }

    public function importRulesForCity(array $cityData, bool $force): array
    {
        $city_id = $cityData["city_id"];
        $country_id = $cityData["country_id"];
        $city_name = $cityData["city_name"];
        $country_name = $cityData["country_name"];

        $promptENG = "Act as a helpful assistant. Explain what are the legal limitations for riding electric scooters in $city_name, $country_name? Contain information about: max speed, helmet requirements, allowed ABV, passengers, other relevant details. Be formal, speak English. Don't include city name in your response. If you don't have information answering the question, write 'null'";
        $promptPL = "Zachowuj się jako pomocny asystent. wyjaśnij jakie są prawa dotyczące jazdy na hulajnogach elektrycznych w $city_name, $country_name? Zawrzyj informacje o: maksymalnej prędkości, potrzebie kasku, dozwolonym alkoholu we krwi, pasażerach, inne. Bądź formalny, mów po polsku. Nie zawieraj nazwy miasta w odpowiedzi. Jeśli nie masz informacji odpowiadających na pytanie, napisz 'null'";

        if (!$force) {
            $currentRules = Rules::query()->where("city_id", $city_id)->where("country_id", $country_id)->first();

            if ($currentRules !== null && $currentRules->rulesENG !== null && $currentRules->rulesPL !== null) {
                return [
                    "city" => $city_name,
                    "country" => $country_name,
                    "rulesENG" => $currentRules->rulesENG,
                    "rulesPL" => $currentRules->rulesPL,
                ];
            }
        }

        $currentRulesInCountry = Rules::query()->where("country_id", $country_id)->first();

        if (in_array($country_name, $this->countriesKnownToHaveUniformRules()) && $currentRulesInCountry !== null && $currentRulesInCountry->rulesENG !== null && $currentRulesInCountry->rulesPL !== null && !$force) {
            $rulesENG = $currentRulesInCountry->rulesENG;
            $rulesPL = $currentRulesInCountry->rulesPL;
        } else {
            $rulesENG = $this->askGPT($promptENG);
            $rulesPL = $this->askGPT($promptPL);
        }

        if (strlen($rulesENG) < 700 || strlen($rulesPL) < 700) {
            return [
                "city" => $city_name,
                "country" => $country_name,
                "rulesENG" => null,
                "rulesPL" => null,
            ];
        }

        $rulesENG = str_replace("\n", "<br>", $rulesENG);
        $rulesPL = str_replace("\n", "<br>", $rulesPL);

        Rules::query()->updateOrCreate([
            "city_id" => $city_id,
            "country_id" => $country_id,
            "rulesENG" => $rulesENG,
            "rulesPL" => $rulesPL,
        ]);
        return [
            "city" => $city_name,
            "country" => $country_name,
            "rulesENG" => $rulesENG,
            "rulesPL" => $rulesPL,
        ];
    }

    private function countriesKnownToHaveUniformRules(): array
    {
        return [
            "Poland", "Germany", "France", "Spain", "Italy", "Portugal", "Austria", "Czech Republic", "Slovakia", "Hungary", "Romania", "Bulgaria", "Greece", "Sweden", "Finland",
            "Norway", "Denmark", "Netherlands", "Belgium", "Switzerland", "Slovenia", "Croatia", "Serbia", "Bosnia and Herzegovina", "Montenegro", "Albania", "North Macedonia",
            "Kosovo", "Ukraine", "Belarus", "Lithuania", "Latvia", "Estonia", "Russia", "Ireland", "United Kingdom", "Turkey",
            "Cyprus", "Malta", "Iceland", "Luxembourg", "Moldova", "Liechtenstein", "Andorra", "Monaco", "San Marino", "Vatican City",
        ];
    }
}
