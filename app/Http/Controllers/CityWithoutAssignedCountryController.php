<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CityWithoutAssignedCountry;

class CityWithoutAssignedCountryController extends Controller
{
    public function destroy(CityWithoutAssignedCountry $city): void
    {
        $city->delete();
    }

    public function destroyAll(): void
    {
        CityWithoutAssignedCountry::query()->delete();
    }
}
