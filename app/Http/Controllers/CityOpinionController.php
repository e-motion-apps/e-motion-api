<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CityOpinionRequest;
use App\Models\CityOpinion;

class CityOpinionController extends Controller
{
    public function store(CityOpinionRequest $request): void
    {
        $request->user()
            ->cityOpinions()
            ->create($request->validated());
    }

    public function update(CityOpinionRequest $request, CityOpinion $cityOpinion): void
    {
        $cityOpinion->update($request->validated());
    }

    public function destroy(CityOpinion $cityOpinion): void
    {
        $cityOpinion->delete();
    }
}
