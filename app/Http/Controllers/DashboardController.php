<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\ImportInfo;
use App\Models\Provider;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $importInfo = ImportInfo::with("importInfoDetail")->orderByDesc("created_at")->get();
        $codes = Code::all();
        $providers = Provider::all();

        return Inertia::render("Dashboard/Index", [
            "importInfo" => $importInfo,
            "codes" => $codes,
            "providers" => $providers,
        ]);
    }
}
