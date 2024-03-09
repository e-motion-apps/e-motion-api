<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CityOpinionRequest;
use App\Models\CityOpinion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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

        $response = Gate::inspect("update", $cityOpinion);

        if ($response->allowed()) {
            $cityOpinion->update($opinion);
        } else {
            abort(403, $response->message());
        }
    }

    public function destroy(CityOpinion $cityOpinion): void
    {
        $response = Gate::inspect("delete", $cityOpinion);

        if ($response->allowed()) {
            $cityOpinion->delete();
        } else {
            abort(403, $response->message());
        }
    }
}
