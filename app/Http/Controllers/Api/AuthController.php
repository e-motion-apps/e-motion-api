<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        ], Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $remember = $request->boolean("remember", false);

        if (Auth::attempt([
            "email" => $request->email,
            "password" => $request->password,
        ], $remember)) {
            $user = Auth::user();
            $user_id = (string)Auth::id();

            $token_abilities = $this->getUserAbilities($user);

            $token = $user->createToken($user_id, $token_abilities)->plainTextToken;

            return response()->json([
                "abilities" => $token_abilities,
                "user" => $user,
                "access_token" => $token,
            ]);
        }

        return response()->json([
            "message" => __("Invalid credentials."),
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            "message" => __("Logged out."),
        ]);
    }

    public function redirectToProvider(string $provider): JsonResponse
    {
        $redirect_url = Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();

        return response()->json([
            "redirect_url" => $redirect_url,
        ]);
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
            $token_abilities = $this->getUserAbilities($user);

            $user_id = (string)$user->id;
            $token = $user->createToken($user_id, $token_abilities)->plainTextToken;

            return response()->json([
                "access_token" => $token,
            ]);
        } catch (Exception $e) {
            return response()->json([
                "message" => __("Login failed."),
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    private function getUserAbilities(Authenticatable $user): array
    {
        $abilities = [];

        if ($user->isAdmin()) {
            $abilities[] = "HasAdminRole";
        }

        return $abilities;
    }
}
