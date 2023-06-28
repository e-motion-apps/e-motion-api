<?php

declare(strict_types=1);

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CountryController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

Route::middleware("guest")->group(function (): void {
    Route::get("/signup", fn(): Response => Inertia::render("Auth/Register"));
    Route::get("/login", [LoginController::class, "create"])->name("getlogin");
    Route::post("/login", [LoginController::class, "login"])->name("login");
    Route::post("/register", [RegisterController::class, "store"])->name("register");
});
Route::resource("countries", CountryController::class);

Route::middleware("auth")->group(function (): void {
    Route::post("/logout", [LogoutController::class, "logout"])->name("logout");
    Route::get("/dashboard", fn(): Response => inertia("Dashboard"))->name("dashboard");
});

Route::get("/", fn(): Response => inertia("Welcome"))->name("home");

Route::group(["middleware" => ["auth", "admin"]], function (): void {
    Route::get("/admin", [AdminController::class, "admin"])->name("adminDashboard");
});
