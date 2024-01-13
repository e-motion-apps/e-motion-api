<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function store(RegisterRequest $request): Response
    {
        $user = User::create([
            "name" => $request->input("name"),
            "email" => $request->input("email"),
            "password" => Hash::make($request->input("password")),
            "logged_by" => "registration",
        ]);

        Auth::login($user);

        return back();
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $remember = $request->boolean("remember", false);

        if (Auth::attempt([
            "email" => $request->email,
            "password" => $request->password,
        ], $remember)) {
            return back();
        }

        return back()->withErrors([
            "loginError" => __("Invalid password or username."),
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route("home");
    }

    public function redirectToProvider(string $provider): Response
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderRedirect(string $provider): RedirectResponse
    {
        try {
            $user = Socialite::driver($provider)->user();

            $user = User::firstOrCreate([
                "email" => $user->getEmail(),
            ], [
                "name" => $user->getName(),
                "password" => Hash::make(Str::password(8)),
                "logged_by" => "socialmedia",
            ]);

            Auth::login($user);
        } catch (\Exception $e) {
            return redirect()->route("home");
        }

        return redirect()->route("home");
    }
}
