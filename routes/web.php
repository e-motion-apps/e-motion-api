<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CountryController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

Route::get("/signup", fn(): Response => inertia::render("Auth/Signup"));
Route::get("/login", [LoginController::class, "create"])->name("login");
Route::post("/login", [LoginController::class, "login"])->name("login");
Route::post("/register", [RegisterController::class, "store"])->name("register");
Route::post("/logout", [LogoutController::class, "logout"])->name("logout");
Route::get("/", fn(): Response => inertia("Welcome"))->name("home");
Route::middleware("auth")->get("/dashboard", fn(): Response => inertia("Dashboard"))->name("dashboard");
Route::resource("countries", CountryController::class);
