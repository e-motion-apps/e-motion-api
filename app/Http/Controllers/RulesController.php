<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ProviderResource;
use App\Models\City;
use App\Models\CityProvider;
use App\Models\Country;
use App\Models\Provider;
use Inertia\Inertia;
use Inertia\Response;

class RulesController
{
    public function getRules($city, $country): string
    {
        $rules = "rules in $city $country";

        return $rules;
    }

    public function index() : Response
    {
        return Inertia::render("Rules/Index", [
            "country" => $country,
            "city" => $city,
        ]);
    }
}
