<?php

declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\Client;
use Throwable;

class MapboxGeocodingService
{
    protected string $city;
    protected string $country;

    public function getCoordinatesFromApi(string $cityName, string $countryName, $client = new Client()): array
    {
        $token = config("app.mapbox_token");

        try {
            $response = $client->get(
                config("app.mapbox_api_url") . "$cityName,$countryName.json?access_token=$token&types=place",
            );

            $coordinates = json_decode($response->getBody()->getContents(), associative: true)["features"][0]["center"];

            return [$coordinates[1], $coordinates[0]];
        } catch (Throwable) {
            return [];
        }
    }

    public function getPlaceFromApi(string $lat, string $long, $client = new Client()): array
    {
        $token = config("app.mapbox_token");

        try {
            $response = $client->get(
                config("app.mapbox_api_url") . "$long,$lat.json?access_token=$token",
            );
            $features = json_decode($response->getBody()->getContents(), associative: true)["features"];

            foreach ($features as $key) {
                $id = $key["id"];

                if (str_contains($id, "place")) {
                    $this->city = $key["text"];
                } elseif (str_contains($id, "locality") && !isset($this->city)) {
                    $this->city = $key["text"];
                }

                if (str_contains($id, "country")) {
                    $this->country = $key["text"];
                }
            }

            return [$this->city, $this->country];
        } catch (Throwable) {
            return [];
        }
    }
}
