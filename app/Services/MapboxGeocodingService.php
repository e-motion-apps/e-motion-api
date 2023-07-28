<?php

declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\Client;
use Throwable;

class MapboxGeocodingService
{
    public function getCoordinatesFromApi(string $cityName, string $countryName): array
    {
        $token = env("MAPBOX_TOKEN");
        $client = new Client();

        try {
            $response = $client->get(
                "https://api.mapbox.com/geocoding/v5/mapbox.places/$cityName,$countryName.json?access_token=$token&types=place",
            );

            $coordinates = json_decode($response->getBody()->getContents(), true)["features"][0]["center"];

            return [$coordinates[1], $coordinates[0]];
        } catch (Throwable) {
            return [];
        }
    }
}
