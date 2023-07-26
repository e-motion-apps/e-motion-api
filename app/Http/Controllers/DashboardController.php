<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\DashboardResource;
use App\Models\Code;
use App\Models\ImportInfo;
use App\Models\Provider;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $importInfo = ImportInfo::query()
            ->with("importInfoDetail")
            ->orderByDesc("created_at")
            ->paginate(5)
            ->withQueryString();
        $codes = Code::all();
        $providers = Provider::all();

        return Inertia::render("Dashboard/Index", [
            "importInfo" => DashboardResource::collection($importInfo),
            "codes" => $codes,
            "providers" => $providers,
        ]);
    }
}
