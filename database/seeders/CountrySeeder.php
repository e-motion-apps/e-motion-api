<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Country;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $client = new Client();
        $response = $client->get("https://restcountries.com/v3.1/all");
        $countriesFromApi = json_decode($response->getBody()->getContents(), true);

        usort($countriesFromApi, fn($a, $b): int => strcmp($a["name"]["common"], $b["name"]["common"]));

        $file = storage_path("app/countries.json");
        $countriesToFile = [];

        foreach ($countriesFromApi as $countryFromApi) {
            if ($countryFromApi["altSpellings"][0] === "BES islands") {
                $countryFromApi["altSpellings"][0] = "bq";
            } else if ($countryFromApi["altSpellings"][0] === "Saudi") {
                $countryFromApi["altSpellings"][0] = "sa";
            }

            Country::firstOrCreate(
                ["iso" => strtolower($countryFromApi["altSpellings"][0])],
                [
                    "name" => $countryFromApi["name"]["common"],
                    "latitude" => $countryFromApi["latlng"][0],
                    "longitude" => $countryFromApi["latlng"][1],
                ],
            );

            $countriesToFile[] = [
                "name" => $countryFromApi["name"]["common"],
                "latitude" => $countryFromApi["latlng"][0],
                "longitude" => $countryFromApi["latlng"][1],
                "iso" => strtolower($countryFromApi["altSpellings"][0]),
            ];
        }

        file_put_contents($file, json_encode($countriesToFile));
    }
}
