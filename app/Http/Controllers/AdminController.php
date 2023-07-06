<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\http\RedirectResponse;
use Inertia\Response;

class AdminController extends Controller
{
    public function admin(): RedirectResponse|Response
    {
        return inertia()->render("Admin");
    }
}
