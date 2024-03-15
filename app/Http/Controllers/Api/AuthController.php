<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;
class AuthController extends Controller
{
    public function store(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            "name" => $request->input("name"),
            "email" => $request->input("email"),
            "password" => Hash::make($request->input("password")),
        ]);

        return response()->json([
            "message" => __("User created."),
        ]);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $remember = $request->boolean("remember", false);

        if (Auth::attempt([
            "email" => $request->email,
            "password" => $request->password,
        ], $remember)) {
            $user = Auth::user();
            $user_id = Auth::id();
            $token = $user->createToken($user_id . "-AuthToken")->plainTextToken;

            return response()->json([
                "access_token" => $token,
            ]);
        }

        return response()->json([
            "message" => __("Invalid credentials."),
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function logout(): JsonResponse
    {
        \auth()->user()->tokens()->delete();

        return response()->json([
            "message" => __("Logged out."),
        ]);
    }

    public function redirectToProvider(string $provider): Response
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderRedirect(string $provider): JsonResponse
    {
        try {
            $user = Socialite::driver($provider)->user();

            $user = User::firstOrCreate([
                "email" => $user->getEmail(),
            ], [
                "name" => $user->getName(),
                "password" => Hash::make(Str::password(8)),
            ]);
            $user_id = $user->id;
            $token = $user->createToken($user_id . "-Socialite-AuthToken")->plainTextToken;

            return JsonResponse::create([
                "access_token" => $token,
            ]);
        } catch (\Exception $e) {
            return JsonResponse::create([
                "message" => $e->getMessage(),
            ]);
        }



    }
}
