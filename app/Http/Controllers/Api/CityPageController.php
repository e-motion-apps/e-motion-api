<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityOpinionResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\ProviderResource;
use App\Models\City;
use App\Models\Country;
use App\Models\Provider;
use Illuminate\Http\JsonResponse;

class CityPageController extends Controller
{
    public function index(Country $country, City $city): JsonResponse
    {
        $selectedCity = City::query()
            ->whereBelongsTo($country)
            ->where("id", $city->id)
            ->with("cityProviders", "country")
            ->firstOrFail();

        $providers = Provider::all();

        $cityOpinions = $selectedCity->cityOpinions()->with(["user"])->orderByDesc("updated_at")->paginate("4")->withQueryString();

        return response()->json([
            "city" => new CityResource($selectedCity),
            "providers" => ProviderResource::collection($providers),
            "cityOpinions" => CityOpinionResource::collection($cityOpinions),
        ]);
    }
}
