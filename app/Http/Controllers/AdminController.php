<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;

class AdminController extends Controller
{
    public function admin(): RedirectResponse|Response
    {
        if (Auth::check() && Auth::user()->admin()) {
            return inertia()->render("Admin");
        }

        return redirect()->route("login");
    }
}
