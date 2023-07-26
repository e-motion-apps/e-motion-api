<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityAlternativeNameController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CityProviderController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware("guest")->group(function (): void {
    Route::post("/login", [AuthController::class, "login"])->name("login");
    Route::post("/register", [AuthController::class, "store"])->name("register");
});

Route::middleware("auth")->group(function (): void {
    Route::post("/logout", [AuthController::class, "logout"])->name("logout");

    Route::middleware(["role:admin"])->group(function (): void {
        Route::get("/admin/dashboard", [DashboardController::class, "index"]);
        Route::resource("/admin/countries", CountryController::class);
        Route::resource("/admin/cities", CityController::class);
        Route::resource("/city-alternative-name", CityAlternativeNameController::class);
        Route::patch("/update-city-providers/{city}", [CityProviderController::class, "update"]);

        Route::get("/run-importers", [CityProviderController::class, "runImporters"]);
    });
});

Route::inertia("/", "Landing/Index")->name("home");
