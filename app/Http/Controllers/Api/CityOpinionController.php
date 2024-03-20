<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityOpinionRequest;
use App\Models\CityOpinion;
use Illuminate\Http\JsonResponse;

class CityOpinionController extends Controller
{
    public function store(CityOpinionRequest $request): JsonResponse
    {
        $opinion = $request->validated();
        $opinion["user_id"] = $request->user()->id;
        CityOpinion::query()->create($opinion);

        return response()->json([
            "message" => __("Opinion added successfully."),
        ]);
    }

    public function update(CityOpinionRequest $request, CityOpinion $cityOpinion): JsonResponse
    {
        $opinion = $request->validated();
        $opinion["user_id"] = $request->user()->id;

        $cityOpinion->update($opinion);

        return response()->json([
            "message" => __("Opinion edited successfully."),
        ]);
    }

    public function destroy(CityOpinion $cityOpinion): JsonResponse
    {
        $cityOpinion->delete();

        return response()->json([
            "message" => __("Opinion removed successfully!"),
        ]);
    }
}
