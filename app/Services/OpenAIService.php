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
use Illuminate\Support\Facades\Storage;
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

        $this->countriesKnownToHaveUniformRules = Storage::disk("public")->json("citiesKnownToHaveUniformRules.json");
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

        $promptEn = "Act as a helpful assistant. Explain what are the legal limitations for riding electric scooters in $cityName, $countryName? Contain information about: max speed, helmet requirements, allowed ABV, passengers, other relevant details. Be formal, speak English. Don't include city name in your response. If you don't have information answering the question, write 'null'.";
        $promptPl = "Translate to polish: ";
        $currentRulesInCountry = Rules::query()->where("country_id", $countryId)->first();

        if ($this->checkIfRulesExist($countryName, $currentRulesInCountry) && !$force) {
            $rulesEn = $currentRulesInCountry->rules_en;
            $rulesPl = $currentRulesInCountry->rules_pl;
        } else {
            $rulesEn = $this->askGPT($promptEn);
            $rulesPl = $this->askGPT($promptPl . $rulesEn);
        }

        if (strlen($rulesEn) < 700 || strlen($rulesPl) < 700) {
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
            "rules_en" => $rulesEn,
            "rules_pl" => $rulesPl,
        ]);

        return [
            "city" => $cityName,
            "country" => $countryName,
            "rules_en" => $rulesEn,
            "rules_pl" => $rulesPl,
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

    private function checkIfRulesExist(string $countryName, object $currentRulesInCountry): bool
    {
        return in_array($countryName, $this->countriesKnownToHaveUniformRules, true) && $currentRulesInCountry !== null && $currentRulesInCountry->rules_en !== null && $currentRulesInCountry->rules_pl !== null;
    }
}
