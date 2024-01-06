<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class RulesController
{
    public function getRules($city, $country): string
    {
        $rules = "rules in $city $country";

        return $rules;
    }

    public function index(Country $country, City $city)
    {
        return Inertia::render("Rules/Index", [
            "rules" => $this->getRules($city, $country),
        ]);
    }
}
