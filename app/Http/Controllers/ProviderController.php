<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProviderRequest;
use App\Http\Resources\ProviderResource;
use App\Models\Provider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\File;

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

        $fileName = strtolower($request["name"]) . "." . $request->file("file")->getClientOriginalExtension();
        $fileContents = $request->file("file")->get();

        Storage::disk("public")->put("providers/" . $fileName, $fileContents);

        return redirect()->back()
            ->with("success");
    }

    public function update(ProviderRequest $request, Provider $provider): void
    {
        $provider->update($request->validated());
    }

    public function destroy(Provider $provider): void
    {
        $provider->delete();
        $filePath = storage_path("app/public/providers/" . strtolower($provider["name"]).".png");
        File::delete($filePath);
    }

    public function showLogo(string $filename): \Illuminate\Http\Response
    {
        $imagePath = storage_path("app/public/providers/" . $filename);

        if (!file_exists($imagePath)) {
            $imagePath =  storage_path("app/public/providers/unknown.png");
        }

        return response(file_get_contents($imagePath), 200, ["Content-Type" => "image/png"]);
    }
}
