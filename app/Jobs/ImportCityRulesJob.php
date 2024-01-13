<?php

namespace App\Jobs;

use App\Services\OpenAIService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class ImportCityRulesJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use SerializesModels;

    private int $cityId;
    private int $countryId;
    private string $cityName;
    private string $countryName;
    private bool $force;

    public function __construct(array $cityData, $force)
    {
    $this->cityId = $cityData["city_id"];
    $this->countryId = $cityData["country_id"];
    $this->cityName = $cityData["city_name"];
    $this->countryName = $cityData["country_name"];
    $this->force = $force;
    }

public function handle(OpenAIService $openAIService)
    {
        $cityData = [
            "city_id" => $this->cityId,
            "country_id" => $this->countryId,
            "city_name" => $this->cityName,
            "country_name" => $this->countryName,
        ];
        return  $openAIService->importRulesForCity($cityData, $this->force);
    }

}
