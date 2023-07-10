<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProviderRequest;
use App\Models\City;
use App\Services\DataImporterService;
use App\Services\ProviderService;

class ProviderController extends Controller
{
    public function update(ProviderService $service, ProviderRequest $request, City $city): void
    {
        $service->updateProvider($request->providers, $city);
    }

    public function runImporters(DataImporterService $service): void
    {
        $service->run();
    }
}
