<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProviderRequest;
use App\Http\Resources\ProviderResource;
use App\Models\Provider;
use Inertia\Inertia;
use Inertia\Response;

class ProviderController extends Controller
{
    public function index(): Response
    {
        $providers = Provider::query()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render("Providers/Index", [
            "providers" => ProviderResource::collection($providers),
        ]);
    }

    public function store(ProviderRequest $request): void
    {
        Provider::query()->create($request->validated());
    }

    public function update(ProviderRequest $request, Provider $provider): void
    {
        $provider->update($request->validated());
    }

    public function destroy(Provider $provider): void
    {
        $provider->delete();
    }
}
