<?php

declare(strict_types=1);


use App\Http\Controllers\CityAlternativeNameController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ProviderController;
use Illuminate\Support\Facades\Route;
use Inertia\Response;

Route::get("/", fn(): Response => inertia("Welcome"));

Route::resource("countries", CountryController::class);
Route::resource("cities", CityController::class);

Route::resource("city-alternative-name", CityAlternativeNameController::class);

Route::patch("update-city-providers/{city}", [ProviderController::class, "update"]);

