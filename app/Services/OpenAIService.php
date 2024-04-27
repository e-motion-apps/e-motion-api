<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\OpenAiException;
use App\Jobs\ImportCityRulesJob;
use App\Models\City;
use App\Models\ImportInfo;
use App\Models\ImportInfoDetail;
use App\Models\Rules;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Bus;
use OpenAI;
use Throwable;

class OpenAIService implements ShouldQueue
{
    private OpenAI\Client $client;
    private array $countriesKnownToHaveUniformRules;

    public function __construct()
    {
        try {
            $this->client = OpenAI::client(env("OPENAI_API_KEY"));
        } catch (Throwable $e) {
            throw new OpenAiException();
        }

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

        $importInfo = ImportInfo::query()->create([
            "who_runs_it" => "admin",
            "status" => "running",
        ]);
        $jobs = [];

        foreach ($cities as $city) {
            $cityData = [
                "cityId" => $city->id,
                "countryId" => $city->country_id,
                "cityName" => $city->name,
                "countryName" => $city->country->name,
            ];
            $jobs[] = new ImportCityRulesJob($cityData, $force);
            ImportCityRulesJob::dispatch($cityData, $force);
        }

        Bus::batch($jobs)
            ->catch(function () use ($importInfo): void {
                ImportInfoDetail::query()->updateOrCreate([
                    "import_info_id" => $importInfo->id,
                    "provider_name" => "OpenAI",
                    "code" => 400,
                ]);
            })->finally(function () use ($importInfo): void {
                ImportInfo::query()->where("id", $importInfo->id)->update([
                    "status" => "finished",
                ]);
            })
            ->dispatch();
    }

    public function importRulesForCity(array $cityData, bool $force): array
    {
        $cityId = $cityData["cityId"];
        $countryId = $cityData["countryId"];
        $cityName = $cityData["cityName"];
        $countryName = $cityData["countryName"];

        $prompt_en = "Act as a helpful assistant. Explain what are the legal limitations for riding electric scooters in $cityName, $countryName? Contain information about: max speed, helmet requirements, allowed ABV, passengers, other relevant details. Be formal, speak English. Don't include city name in your response. If you don't have information answering the question, write 'null'.";
        $prompt_pl = "Translate to polish: ";
        $currentRulesInCountry = Rules::query()->where("country_id", $countryId)->first();

        if (in_array($countryName, $this->countriesKnownToHaveUniformRules, true) && $currentRulesInCountry !== null && $currentRulesInCountry->rules_en !== null && $currentRulesInCountry->rules_pl !== null && !$force) {
            $rules_en = $currentRulesInCountry->rules_en;
            $rules_pl = $currentRulesInCountry->rules_pl;
        } else {
            $rules_en = $this->askGPT($prompt_en);
            $rules_pl = $this->askGPT($prompt_pl . $rules_en);
        }

        if (strlen($rules_en) < 700 || strlen($rules_pl) < 700) {
            return [
                "city" => $cityName,
                "country" => $countryName,
                "rules_en" => null,
                "rules_pl" => null,
            ];
        }

        Rules::query()->updateOrCreate([
            "city_id" => $cityId,
            "country_id" => $countryId,
            "rules_en" => $rules_en,
            "rules_pl" => $rules_pl,
        ]);

        return [
            "city" => $cityName,
            "country" => $countryName,
            "rules_en" => $rules_en,
            "rules_pl" => $rules_pl,
        ];
    }

    private function askGPT(string $prompt): string
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
