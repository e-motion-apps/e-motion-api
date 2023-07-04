<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Country;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;

class CitiesAndCountriesSeeder extends Seeder
{
    public function run(): void
    {
        $client = new Client();
        $response = $client->get("https://countriesnow.space/api/v0.1/countries");
        $items = json_decode($response->getBody()->getContents(), true);

        foreach ($items["data"] as $item) {
            Country::firstOrCreate([
                "iso" => strtolower($item["iso2"]),
                "name" => $item["country"],
            ]);
        }
    }
}
