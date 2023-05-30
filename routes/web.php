<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Inertia\ResponseFactory;

Route::get("/", fn(): ResponseFactory => inertia("Welcome"));
