<?php

declare(strict_types=1);

namespace App\Http\Controllers;

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

        if ($cityOpinion->user_id === Auth::id() || Auth::user()->hasRole("admin")) {
            $cityOpinion->update($opinion);
        } else {
            abort(403);
        }
    }

    public function destroy(CityOpinion $cityOpinion): void
    {
        if ($cityOpinion->user_id === Auth::id() || Auth::user()->hasRole("admin")) {
            $cityOpinion->delete();
        } else {
            abort(403);
        }
    }
}
