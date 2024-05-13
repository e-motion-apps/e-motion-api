<?php

declare(strict_types=1);

use App\Http\Controllers\Api\Admin\CityAlternativeNameController;
use App\Http\Controllers\Api\Admin\CityController;
use App\Http\Controllers\Api\Admin\CountryController;
use App\Http\Controllers\Api\Admin\ImportInfoController;
use App\Http\Controllers\Api\Admin\ProviderController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChangeLocaleController;
use App\Http\Controllers\Api\CityOpinionController;
use App\Http\Controllers\Api\CityPageController;
use App\Http\Controllers\Api\CityProviderController;
use App\Http\Controllers\Api\CityWithoutAssignedCountryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\FavoritesController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::name("api.")->group(function (): void {
    Route::middleware("auth:api")->get("/user", fn(Request $request): JsonResponse => new JsonResponse($request->user()));
    Route::get("/providers", [CityProviderController::class, "index"]);

    Route::middleware("guest")->group(function (): void {
        Route::post("/register", [AuthController::class, "store"])->name("register");
        Route::post("/login", [AuthController::class, "login"])->name("login");
        Route::get("/login/{provider}", [AuthController::class, "redirectToProvider"])->name("login.provider");
        Route::get("/login/{provider}/redirect", [AuthController::class, "handleProviderRedirect"])->name("login.provider.redirect");
    });

    Route::middleware("auth:sanctum")->group(function (): void {
        Route::post("/logout", [AuthController::class, "logout"])->name("logout");

        Route::post("/favorites", [FavoritesController::class, "store"]);
        Route::get("/favorites/{city_id}", [FavoritesController::class, "check"]);
        Route::get("/favorite-cities", [FavoritesController::class, "index"]);

        Route::post("/opinions", [CityOpinionController::class, "store"]);
        Route::patch("/opinions/{cityOpinion}", [CityOpinionController::class, "update"])
            ->middleware("can:update,cityOpinion");
        Route::delete("/opinions/{cityOpinion}", [CityOpinionController::class, "destroy"])
            ->middleware("can:delete,cityOpinion");

        Route::middleware("ability:HasAdminRole")->group(function (): void {
            Route::get("/admin/importers", [ImportInfoController::class, "index"]);
            Route::resource("/admin/providers", ProviderController::class);
            Route::resource("/admin/countries", CountryController::class);
            Route::resource("/admin/cities", CityController::class);
            Route::resource("/admin/dashboard", DashboardController::class);
            Route::resource("/city-alternative-name", CityAlternativeNameController::class);
            Route::patch("/update-city-providers/{city}", [CityProviderController::class, "update"]);

            Route::post("/run-importers", [CityProviderController::class, "runImporters"]);
            Route::delete("/delete-city-without-assigned-country/{city}", [CityWithoutAssignedCountryController::class, "destroy"]);
            Route::post("/delete-all-cities-without-assigned-country", [CityWithoutAssignedCountryController::class, "destroyAll"]);
        });
    });
    Route::post("/language/{locale}", ChangeLocaleController::class);

    Route::get("/{country:slug}/{city:slug}", [CityPageController::class, "index"]);

    Route::get("/images/providers/{filename}", [ProviderController::class, "showLogo"]);
});
