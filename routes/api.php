<?php

declare(strict_types=1);

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;

Route::middleware("auth:sanctum")->get("/user", fn(Request $request): JsonResponse => new JsonResponse($request->user()));

Route::post('/register', [RegisterController::class, 'SignUp']);
Route::post('/login', [LoginController::class,'Login']);
Route::post('/logout', [LoginController::class, 'Logout']);
