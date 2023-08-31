<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\City;
use App\Models\Country;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $cityCount = City::count();
        $countryCount = Country::count();
        return Inertia::render("Dashboard/Index", [
            'cityCount' => $cityCount,
            'countryCount' => $countryCount,
        ]);
    }
}
