<?php

namespace App\Http\Controllers;

class RulesController
{
    public function getRules($city, $country) : string
    {
        $rules = "rules in $city $country";
        return $rules;
    }

}
