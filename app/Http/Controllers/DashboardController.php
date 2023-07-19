<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ImportInfo;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $importInfo = ImportInfo::with("importInfoDetail")->get();

        return Inertia::render("Dashboard/Index", [
            "importInfo" => $importInfo,
        ]);
    }
}
