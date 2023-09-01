<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Helpers\I18n;
use App\Models\City;
use App\Models\CityWithoutAssignedCountry;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = "app";

    public function __construct(
        protected Application $application,
    ) {}

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $sharedData = [
            "auth" => [
                "isAuth" => auth()->check(),
                "isAdmin" => optional(auth()->user())->isAdmin(),
                "user" => auth()->user(),
            ],
            "locale" => $this->application->getLocale(),
            "language" => I18n::getTranslations(lang_path($this->application->getLocale() . ".json")),
        ];

        if (auth()->user() && auth()->user()->isAdmin()) {
            $countCitiesWithoutCoordinates = City::query()->whereHas("cityProviders", function ($query): void {
                $query->whereNull("latitude")->whereNull("longitude");
            })->count();
            $sharedData["countCitiesWithoutCoordinates"] = $countCitiesWithoutCoordinates;

            $countCitiesWithoutAssignedCountry = CityWithoutAssignedCountry::count();
            $sharedData["countCitiesWithoutAssignedCountry"] = $countCitiesWithoutAssignedCountry;
        }

        return array_merge(parent::share($request), $sharedData);
    }
}
