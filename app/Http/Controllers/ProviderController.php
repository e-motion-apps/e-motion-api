<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProviderRequest;
use App\Http\Resources\ProviderResource;
use App\Models\Provider;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Storage;

class ProviderController extends Controller
{
    public function index(): Response
    {
        $providers = Provider::query()
            ->orderByName()
            ->orderByTimeRange()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render("Providers/Index", [
            "providers" => ProviderResource::collection($providers),
        ]);
    }

    public function store(ProviderRequest $request): RedirectResponse
    {
        Provider::query()->create($request->validated());

        $fileName = $request['name'] . '.png';// . $request->file('file')->getClientOriginalExtension();
        $fileContents = $request->file('file')->get();

        Storage::disk('local')->put('public/providers/' . $fileName, $fileContents);

        return redirect()->back()
            ->with("success", __("Provider created successfully."));
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
