<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
