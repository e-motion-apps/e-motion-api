<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ImportInfoResource;
use App\Models\Code;
use App\Models\ImportInfo;
use App\Models\Provider;
use Illuminate\Http\JsonResponse;

class ImportInfoController extends Controller
{
    public function index(): JsonResponse
    {
        $importInfo = ImportInfo::query()
            ->with("importInfoDetails")
            ->orderByDesc("created_at")
            ->paginate(15)
            ->withQueryString();
        $codes = Code::all();
        $providers = Provider::all();

        return response()->json([
            "importInfo" => ImportInfoResource::collection($importInfo),
            "codes" => $codes,
            "providers" => $providers,
        ]);
    }
}
