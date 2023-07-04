<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Services\MapboxGeocodingService;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;

class CitiesAndCountriesSeeder extends Seeder
{
    public function run(): void
    {
        $client = new Client();
        $response = $client->get("https://countriesnow.space/api/v0.1/countries");
        $items = json_decode($response->getBody()->getContents(), true);

        $mapboxService = new MapboxGeocodingService();

        foreach ($items["data"] as $item) {
            $country = Country::firstOrCreate([
                "iso" => strtolower($item["iso2"]),
                "name" => $item["country"]
            ]);
//
//            $cities = $item["cities"];
//            $citiesCount = count($cities);
//
//            for ($i = 0; $i < $citiesCount; $i += 150) {
//                $city = $cities[$i];
//                $coordinates = $mapboxService->getCoordinatesFromApi($city, $item["country"]);
//                $countCoordinates = count($coordinates);
//
//                City::query()->updateOrCreate([
//                    "country_id" => $country->id,
//                    "name" => $city,
//                    "latitude" => ($countCoordinates > 0) ? $coordinates[0] : null,
//                    "longitude" => ($countCoordinates > 0) ? $coordinates[1] : null,
//                ]);
//            }
        }
    }

}
