<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProviderResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class UserProfileController extends Controller
{
    public function index(): Response
    {
        $user = Auth::user();

        return Inertia::render("UserProfile/Index", [
            "user" => $user,
        ]);
    }
}
