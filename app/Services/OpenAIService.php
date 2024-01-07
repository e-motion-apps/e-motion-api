<?php

declare(strict_types=1);

namespace App\Http;

use App\Models\City;
use Exception;
use OpenAI;

class OpenAIService
{
    public function getRules()
    {
        $responseENG = "Sorry, we couldn't find any rules for this city.";
        $responsePL = "Przepraszamy, nie udało nam się znaleźć żadnych zasad dla tego miasta.";

        try {
            $client = OpenAI::client(env("OPENAI_API_KEY"));

            $cities = City::all();
            $data = [];

            foreach ($cities as $city) {
                $data[] = ["city" => $city, "country" => $city->country, "rulesENG" => $responseENG, "rulesPL" => $responsePL];

                return $data;
            }

            foreach ($data as $i) {
                $promptENG = "What are the legal limitations for riding electric scooters in " . $i->name . ", " . $i->country->name . "? Contain information about: max speed, helmet requirements, allowed ABV, passengers, other relevant details. Be formal, end with a legal disclaimer.";
                $promptPL = "Jakie są prawa dotyczące jazdy na hulajnogach elektrycznych w " . $i["city"] . ", " . $i["country"] . "? Zawrzyj informacje o: maksymalnej prędkości, potrzebie kasku, dozwolonym alkoholu we krwi, pasażerach, inne. Bądź formalny, zakończ oświadczeniem prawnym.";

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

                $i["rulesENG"] = $responseENG["choices"][0]["text"];
                $i["rulesPL"] = $responsePL["choices"][0]["text"];
            }
        } catch (Exception $e) {
        }
    }
}
