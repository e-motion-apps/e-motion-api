<?php

declare(strict_types=1);

use App\Http\Controllers\CountryController;
use Illuminate\Support\Facades\Route;
use Inertia\Response;

Route::get("/", fn(): Response => inertia("Welcome"));

Route::resource("countries", CountryController::class);
