<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::middleware("guest")->group(function (): void {});

Route::middleware("auth")->group(function (): void {});
