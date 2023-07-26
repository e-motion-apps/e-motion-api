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

        $existingFavorite = Favorites::where("user_id", request()->user()?->id)
            ->where("city_id", $cityId)
            ->first();

        if (!$existingFavorite) {
            Favorites::create([
                "user_id" => request()->user()?->id,
                "city_id" => $cityId,
            ]);
            $session->flash("message", "City added to favorites!");
        } else {
            $existingFavorite->delete();
            $session->flash("message", "City removed from favorites!");
        }
    }

    public function check($cityId): bool
    {
        return Favorites::where("user_id", request()->user()?->id)
            ->where("city_id", $cityId)
            ->exists();
    }
}
