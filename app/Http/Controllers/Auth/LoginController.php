<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class LoginController extends Controller
{
    public function create(): InertiaResponse
    {
        return Inertia::render("Auth/Login");
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $remember = $request->boolean("remember", false);

        if (Auth::attempt($credentials, $remember)) {
            return Inertia::location("/");
        }
        
       return back()->withErrors([
                "email" => "Error message"),
            ]);
    }
}
