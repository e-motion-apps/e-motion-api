<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityAlternativeNameController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CityProviderController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;

Route::middleware("guest")->group(function (): void {
    Route::post("/login", [AuthController::class, "login"])->name("login");
    Route::post("/register", [AuthController::class, "store"])->name("register");
});

Route::middleware("auth")->group(function (): void {
    Route::post("/logout", [AuthController::class, "logout"])->name("logout");
    Route::get("/notes", [NoteController::class, "index"])->name("notes.index");
    Route::post("/notes", [NoteController::class, "store"])->name("notes.store");
    Route::delete("/notes/{note}", [NoteController::class, "destroy"])->name("notes.destroy");

    Route::middleware(["role:admin"])->group(function (): void {
        Route::get("/admin/dashboard", [DashboardController::class, "index"]);
        Route::resource("/admin/dashboard/countries", CountryController::class);
        Route::resource("/admin/dashboard/cities", CityController::class);
        Route::resource("/city-alternative-name", CityAlternativeNameController::class);
        Route::patch("/update-city-providers/{city}", [CityProviderController::class, "update"]);
    });
});

Route::inertia("/", "Landing/Index")->name("home");

Route::get("/run-importers", [CityProviderController::class, "runImporters"]);
