<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityAlternativeNameRequest;
use App\Models\CityAlternativeName;

class CityAlternativeNameController extends Controller
{
    public function store(CityAlternativeNameRequest $request): void
    {
        CityAlternativeName::query()->create($request->validated());
    }

    public function destroy(CityAlternativeName $cityAlternativeName): void
    {
        $cityAlternativeName->delete();
    }
}
