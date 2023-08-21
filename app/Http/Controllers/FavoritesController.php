<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Favorites;
use Illuminate\Http\Request;
use Illuminate\Session\Store as Session;

class FavoritesController extends Controller
{
    public function store(Request $request, Session $session): void
    {
        $cityId = $request->input("city_id");
        $userId = $request->user()?->id;

        $favorite = Favorites::firstOrCreate(
            ["user_id" => $userId, "city_id" => $cityId],
        );

        if ($favorite->wasRecentlyCreated) {
            $session->flash("message", "City added to favorites.");
        } else {
            $favorite->delete();
            $session->flash("message", "City removed from favorites.");
        }
    }

    public function check(Request $request, int $cityId): bool
    {
        $userId = $request->user()?->id;

        return Favorites::where("user_id", $userId)
            ->where("city_id", $cityId)
            ->exists();
    }

    public function show(Request $request): array
    {
        $userId = $request->user()?->id;

        return Favorites::where("user_id", $userId)
            ->get()
            ->pluck("city_id")
            ->toArray();
    }
}
