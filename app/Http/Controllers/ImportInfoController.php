<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ImportInfoResource;
use App\Models\Code;
use App\Models\ImportInfo;
use App\Models\Provider;
use Inertia\Inertia;

class ImportInfoController extends Controller
{
    public function index()
    {
        $importInfo = ImportInfo::query()
            ->with("importInfoDetail")
            ->orderByDesc("created_at")
            ->paginate(15)
            ->withQueryString();
        $codes = Code::all();
        $providers = Provider::all();

        return Inertia::render("Importers/Index", [
            "importInfo" => ImportInfoResource::collection($importInfo),
            "codes" => $codes,
            "providers" => $providers,
        ]);
    }
}
