<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;

class RulesController
{
    public function getRules($city, $country): string
    {
        $rules = "rules in $city $country";

        return $rules;
    }

    public function index($country, $city)
    {
        return Inertia::render("Rules/Index", [
            "country" => $country,
            "city" => $city,
        ]);
    }
}
