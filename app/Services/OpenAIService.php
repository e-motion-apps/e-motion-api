<?php

declare(strict_types=1);

namespace App\Services;

use App\Jobs\ImportCityRulesJob;
use App\Models\City;
use App\Models\Rules;
use Illuminate\Contracts\Queue\ShouldQueue;
use OpenAI;

class OpenAIService implements ShouldQueue
{
    private OpenAI\Client $client;
    private array $countriesKnownToHaveUniformRules;

    public function __construct()
    {
        $this->client = OpenAI::client(env("OPENAI_API_KEY"));
        $this->countriesKnownToHaveUniformRules = [
            "Poland", "Germany", "France", "Spain", "Italy", "Portugal", "Austria", "Czech Republic", "Slovakia", "Hungary",
            "Romania", "Bulgaria", "Greece", "Sweden", "Finland", "Norway", "Denmark", "Netherlands", "Belgium", "Switzerland",
            "Slovenia", "Croatia", "Serbia", "Bosnia and Herzegovina", "Montenegro", "Albania", "North Macedonia", "Kosovo",
            "Ukraine", "Belarus", "Lithuania", "Latvia", "Estonia", "Russia", "Ireland", "United Kingdom", "Turkey", "Cyprus",
            "Malta", "Iceland", "Luxembourg", "Moldova", "Liechtenstein", "Andorra", "Monaco", "San Marino", "Vatican City",
            "Armenia", "Azerbaijan", "Georgia", "Israel", "Japan", "New Zealand", "Philippines", "South Korea", "Singapore",
            "Thailand", "Vietnam", "Bangladesh", "Bhutan", "Brunei", "Cambodia", "East Timor", "Fiji", "Kiribati", "Laos",
            "Maldives", "Marshall Islands", "Micronesia", "Mongolia", "Myanmar", "Nauru", "Nepal", "Palau", "Papua New Guinea",
            "Samoa", "Solomon Islands", "Sri Lanka", "Tonga", "Tuvalu", "Vanuatu", "Algeria", "Angola", "Benin", "Botswana",
            "Burkina Faso", "Burundi", "Cabo Verde", "Cameroon", "Central African Republic", "Chad", "Comoros", "Congo",
            "Djibouti", "Egypt", "Equatorial Guinea", "Eritrea", "Eswatini", "Ethiopia", "Gabon", "Gambia", "Ghana", "Guinea",
            "Guinea-Bissau", "Ivory Coast", "Kenya", "Lesotho", "Liberia", "Libya", "Madagascar", "Malawi", "Mali", "Mauritania",
            "Mauritius", "Morocco", "Mozambique", "Namibia", "Niger", "Nigeria", "Rwanda", "Sao Tome and Principe", "Senegal",
            "Seychelles", "Sierra Leone", "Somalia", "South Africa", "South Sudan", "Sudan", "Tanzania", "Togo", "Tunisia", "Uganda",
            "Zambia", "Zimbabwe", "Afghanistan", "Bahrain", "Iran", "Iraq", "Jordan", "Kuwait", "Lebanon", "Oman",
            "Qatar", "Saudi Arabia", "Syria", "United Arab Emirates", "Yemen", "Albania", "Andorra", "Armenia", "Austria",
            "Azerbaijan", "Belarus", "Belgium", "Bosnia and Herzegovina", "Bulgaria", "Croatia", "Cyprus", "Czech Republic",
            "Denmark", "Estonia", "Finland", "France", "Georgia", "Germany", "Greece", "Hungary", "Iceland", "Ireland",
            "Italy", "Kazakhstan", "Kosovo", "Latvia", "Liechtenstein", "Lithuania", "Luxembourg", "Malta", "Moldova",
            "Monaco", "Montenegro", "Netherlands", "North Macedonia", "Norway", "Poland", "Portugal", "Romania", "Russia",
            "San Marino", "Serbia", "Slovakia", "Slovenia", "Spain", "Sweden", "Switzerland", "Turkey", "Ukraine", "United Kingdom",
            "Vatican City", "Argentina", "Belize", "Bolivia", "Brazil", "Chile", "Colombia", "Costa Rica", "Cuba", "Dominican Republic",
            "Ecuador", "El Salvador", "Guatemala", "Guyana", "Haiti", "Honduras", "Jamaica", "Mexico", "Nicaragua", "Panama",
            "Paraguay", "Peru", "Suriname", "Uruguay", "Venezuela", "Antigua and Barbuda", "Bahamas", "Barbados", "Dominica",
            "Grenada", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Trinidad and Tobago"];
    }

    public function importRulesForAllCities(bool $force): void
    {
        $cities = City::query()->whereHas("cityProviders")->orderBy("country_id")->get();

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

    public function importRulesForCity(array $cityData, bool $force): array
    {
        $city_id = $cityData["city_id"];
        $country_id = $cityData["country_id"];
        $city_name = $cityData["city_name"];
        $country_name = $cityData["country_name"];

        $promptENG = "Act as a helpful assistant. Explain what are the legal limitations for riding electric scooters in $city_name, $country_name? Contain information about: max speed, helmet requirements, allowed ABV, passengers, other relevant details. Be formal, speak English. Don't include city name in your response. If you don't have information answering the question, write 'null'.";
        $promptPL = "Zachowuj się jako pomocny asystent. wyjaśnij jakie są prawa dotyczące jazdy na hulajnogach elektrycznych w $city_name, $country_name? Zawrzyj informacje o: maksymalnej prędkości, potrzebie kasku, dozwolonym alkoholu we krwi, pasażerach, inne. Bądź formalny, mów po polsku. Nie zawieraj nazwy miasta w odpowiedzi. Jeśli nie masz informacji odpowiadających na pytanie, napisz 'null'.";

        $currentRulesInCountry = Rules::query()->where("country_id", $country_id)->first();

        if (in_array($country_name, $this->countriesKnownToHaveUniformRules, true) && $currentRulesInCountry !== null && $currentRulesInCountry->rulesENG !== null && $currentRulesInCountry->rulesPL !== null && !$force) {
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
}
