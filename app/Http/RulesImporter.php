<?php

namespace App\Http;

use Exception;
use OpenAI;
use App\Models\City;

class RulesImporter
{

    public function importRules()
    {
        $responseENG = "Sorry, we couldn't find any rules for this city.";
        $responsePL = "Przepraszamy, nie udało nam się znaleźć żadnych zasad dla tego miasta.";
        try {
            $client = OpenAI::client(env('OPENAI_API_KEY'));

            $cities = City::all();
            $places = [];
            foreach ($cities as $city){
                $places[] = ['city' => $city->name, "country" => $city->country->name, "rulesENG" => $responseENG, "rulesPL" => $responsePL];
            }


            foreach ($places as $place){


                $promptENG = "What are the legal limitations for riding electric scooters in " . $place['city'] . ", " . $place['country'] . "? Contain information about: max speed, helmet requirements, allowed ABV, passengers, other relevant details. Be formal, end with a legal disclaimer.";
                $promptPL = "Jakie są prawa dotyczące jazdy na hulajnogach elektrycznych w " . $place['city'] . ", " . $place['country'] . "? Zawrzyj informacje o: maksymalnej prędkości, potrzebie kasku, dozwolonym alkoholu we krwi, pasażerach, inne. Bądź formalny, zakończ oświadczeniem prawnym.";

                $responseENG = $client->chat()->create([
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $promptENG,
                        ],

                    ],
                ]);
                $responsePL = $client->chat()->create([
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $promptPL,
                        ],

                    ],
                ]);

                $place['rulesENG'] = $responseENG['choices'][0]['text'];
                $place['rulesPL'] = $responsePL['choices'][0]['text'];
            }


        } catch (Exception $e) {

        }

        //store data, maybe in db

    }

}
