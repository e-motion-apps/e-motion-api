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
        $opinion = $request->validated();
        $opinion["user_id"] = Auth::id();

        CityOpinion::query()->create($opinion);
    }

    public function update(CityOpinionRequest $request, CityOpinion $cityOpinion): void
    {
        $opinion = $request->validated();

        if (Gate::allows("update", $cityOpinion)) {
            $cityOpinion->update($opinion);
        } else {
            abort(403);
        }
    }

    public function destroy(CityOpinion $cityOpinion): void
    {
        if (Gate::allows("delete", $cityOpinion)) {
            $cityOpinion->delete();
        }
    }
}
