<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CountryRequest;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Http\JsonResponse;

class CountryController extends Controller
{
    public function index(): JsonResponse
    {
        $countries = Country::query()
            ->search("name")
            ->orderByName()
            ->orderByTimeRange()
            ->paginate(15)
            ->withQueryString();

        return response()->json([
            "countries" => CountryResource::collection($countries),
        ]);
    }

    public function store(CountryRequest $request): JsonResponse
    {
        Country::query()->create($request->validated());

        return response()->json(["message" => __("Country created successfully.")], 201);
    }

    public function update(CountryRequest $request, Country $country): JsonResponse
    {
        $country->update($request->validated());

        return response()->json(["message" => __("Country updated successfully.")]);
    }

    public function destroy(Country $country): JsonResponse
    {
        $country->delete();

        return response()->json(["message" => __("Country deleted successfully.")]);
    }
}
