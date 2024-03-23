<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityAlternativeNameRequest;
use App\Models\CityAlternativeName;
use Illuminate\Http\JsonResponse;

class CityAlternativeNameController extends Controller
{
    public function store(CityAlternativeNameRequest $request): JsonResponse
    {
        CityAlternativeName::query()->create($request->validated());

        return response()->json(["message" => __("City alternative name created successfully.")], 201);
    }

    public function destroy(CityAlternativeName $cityAlternativeName): JsonResponse
    {
        $cityAlternativeName->delete();

        return response()->json(["message" => __("City alternative name deleted successfully.")]);
    }
}
