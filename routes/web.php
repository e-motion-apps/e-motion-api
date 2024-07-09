<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\CityAlternativeNameController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\ImportInfoController;
use App\Http\Controllers\Admin\ProviderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChangeLocaleController;
use App\Http\Controllers\CityOpinionController;
use App\Http\Controllers\CityPageController;
use App\Http\Controllers\CityProviderController;
use App\Http\Controllers\CityWithoutAssignedCountryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\RulesController;
use App\Models\User;
use App\Notifications\TestNotification;
use Illuminate\Support\Facades\Route;

$router = app("router");
$router->has("login") ? $router->getRoutes()->getByName("login")->uri : null;
Route::middleware("guest")->group(function (): void {
    Route::get("/test-notification", function (): void {
        $user = User::query()->where("email", "admin@example.com")->first();
        $user->notify(new TestNotification());
    } );
    Route::get("/test-view", function () {
        $name = "test";
        $title = "Login";

        return view("index", ["name" => $name, "title" => $title]);
    });

    Route::post("/login", [AuthController::class, "login"])->name("login");
    Route::post("/register", [AuthController::class, "store"])->name("register");

    Route::get("/login/{provider}", [AuthController::class, "redirectToProvider"])->name("login.provider");
    Route::get("/login/{provider}/redirect", [AuthController::class, "handleProviderRedirect"])->name("login.provider.redirect");
});

Route::middleware("auth")->group(function (): void {
    Route::post("/logout", [AuthController::class, "logout"])->name("logout");

    Route::post("/favorites", [FavoritesController::class, "store"]);
    Route::get("/favorites/{city_id}", [FavoritesController::class, "check"]);
    Route::get("/favorite-cities", [FavoritesController::class, "index"]);

    Route::post("/opinions", [CityOpinionController::class, "store"]);
    Route::patch("/opinions/{cityOpinion}", [CityOpinionController::class, "update"])
        ->middleware("can:update,cityOpinion");
    Route::delete("/opinions/{cityOpinion}", [CityOpinionController::class, "destroy"])
        ->middleware("can:delete,cityOpinion");

    Route::middleware(["role:admin"])->group(function (): void {
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
        Route::post("/import-rules", [RulesController::class, "importRules"]);
    });
});

Route::post("/language/{locale}", ChangeLocaleController::class);

Route::inertia("/", "Landing/Index")->name("home");
Route::get("/{country:slug}/{city:slug}", [CityPageController::class, "index"]);

Route::get("/images/providers/{filename}", [ProviderController::class, "showLogo"]);
