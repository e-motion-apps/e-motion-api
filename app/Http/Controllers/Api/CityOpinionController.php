<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityOpinionRequest;
use App\Models\CityOpinion;
use Illuminate\Support\Facades\Auth;

class CityOpinionController extends Controller
{
    public function store(CityOpinionRequest $request): void
    {
        $opinion = $request->only(["rating", "content", "city_id"]);
        $opinion["user_id"] = Auth::id();

        CityOpinion::query()->create($opinion);
    }

    public function update(CityOpinionRequest $request, CityOpinion $cityOpinion): void
    {
        $opinion = $request->only(["rating", "content", "city_id"]);
        $opinion["user_id"] = Auth::id();

        $cityOpinion->update($opinion);
    }

    public function destroy(CityOpinion $cityOpinion): void
    {
        $cityOpinion->delete();
    }
}
