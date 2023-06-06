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

        usort($countriesFromApi, fn($a, $b) => strcmp($a["name"]["common"], $b["name"]["common"]));

        foreach ($countriesFromApi as $countryFromApi) {
            $country = new Country();

            $country->name = $countryFromApi["name"]["common"];
            $country->lat = $countryFromApi["latlng"][0];
            $country->lon = $countryFromApi["latlng"][1];
            $country->iso = $countryFromApi["altSpellings"][0];

            $country->iso = strtolower($country->iso);

            if ($country->iso === "bes islands") {
                $country->iso = "bq";
            } else if ($country->iso === "saudi") {
                $country->iso = "sa";
            }

            $country->save();
        }
    }
}
