<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\CityAlternativeNameController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\ImportInfoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChangeLocaleController;
use App\Http\Controllers\CityOpinionController;
use App\Http\Controllers\CityPageController;
use App\Http\Controllers\CityProviderController;
use App\Http\Controllers\CityWithoutAssignedCountryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\RulesController;
use App\Http\RulesImporter;
use Illuminate\Support\Facades\Route;

Route::middleware("guest")->group(function (): void {
    Route::post("/login", [AuthController::class, "login"])->name("login");
    Route::post("/register", [AuthController::class, "store"])->name("register");

    Route::get("/login/{provider}", [AuthController::class, "redirectToProvider"])->name("login.provider");
    Route::get("/login/{provider}/redirect", [AuthController::class, "handleProviderRedirect"]);
});

Route::middleware("auth")->group(function (): void {
    Route::post("/logout", [AuthController::class, "logout"])->name("logout");

    Route::post("/favorites", [FavoritesController::class, "store"]);
    Route::get("/favorites/{city_id}", [FavoritesController::class, "check"]);
    Route::get("/favorite-cities", [FavoritesController::class, "index"]);

    Route::post("/opinions", [CityOpinionController::class, "store"]);
    Route::patch("/opinions/{cityOpinion}", [CityOpinionController::class, "update"]);
    Route::delete("/opinions/{cityOpinion}", [CityOpinionController::class, "destroy"]);

    Route::middleware(["role:admin"])->group(function (): void {
        Route::get("/admin/importers", [ImportInfoController::class, "index"]);
        Route::resource("/admin/countries", CountryController::class);
        Route::resource("/admin/cities", CityController::class);
        Route::resource("/admin/dashboard", DashboardController::class);
        Route::resource("/city-alternative-name", CityAlternativeNameController::class);
        Route::patch("/update-city-providers/{city}", [CityProviderController::class, "update"]);

        Route::post("/run-importers", [CityProviderController::class, "runImporters"]);
        Route::delete("/delete-city-without-assigned-country/{city}", [CityWithoutAssignedCountryController::class, "destroy"]);
        Route::post("/delete-all-cities-without-assigned-country", [CityWithoutAssignedCountryController::class, "destroyAll"]);

        Route::get("/importRules", [RulesImporter::class, "importRules"]);
    });
});

Route::get("/rules/{country}/{city}", [RulesController::class, "index"]);

Route::post("/language/{locale}", ChangeLocaleController::class);

Route::inertia("/", "Landing/Index")->name("home");
Route::get("/{country:slug}/{city:slug}", [CityPageController::class, "index"]);
