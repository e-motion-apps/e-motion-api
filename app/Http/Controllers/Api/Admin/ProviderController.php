<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProviderRequest;
use App\Http\Resources\ProviderResource;
use App\Models\Provider;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProviderController extends Controller
{
    public const ITEMS_PER_PAGE = 15;

    public function index(): JsonResponse
    {
        $providers = Provider::query()
            ->search("name")
            ->orderByName()
            ->orderByTimeRange()
            ->paginate(self::ITEMS_PER_PAGE)
            ->withQueryString();

        return response()->json([
            "providers" => ProviderResource::collection($providers),
        ]);
    }

    public function store(ProviderRequest $request): void
    {
        Provider::query()->create($request->validated());

        $fileName = $this->getFilename($request);
        $fileContents = $request->file("file")->get();

        Storage::disk("public")->put("providers/" . $fileName, $fileContents);
    }

    public function update(ProviderRequest $request, Provider $provider): void
    {
        $provider->update($request->validated());

        $imageName = $this->getFilename($request);
        $storageImagePath = storage_path("app/public/providers/" . $imageName);
        $resourceImagePath = resource_path("providers/" . $imageName);
        $imageContents = $request->file("file")->get();

        if (file_exists($resourceImagePath)) {
            file_put_contents($resourceImagePath, $imageContents);
            Storage::put($storageImagePath, file_get_contents($imageContents));
        } else {
            Storage::put($storageImagePath, file_get_contents($imageContents));
        }
    }

    public function destroy(Provider $provider): void
    {
        $provider->delete();
        $imagePath = storage_path("app/public/providers/" . strtolower($provider["name"]) . ".png");
        File::delete($imagePath);
    }

    public function showLogo(string $filename): JsonResponse
    {
        $imagePath = storage_path("app/public/providers/" . $filename);

        if (!file_exists($imagePath)) {
            $imagePath = storage_path("app/public/providers/unknown.png");
        }

        $imageData = base64_encode(file_get_contents($imagePath));

        return response()->json([
            "image" => $imageData,
            "mime_type" => "image/png",
        ]);
    }

    public function getFilename(ProviderRequest $request): JsonResponse
    {
        $filename = strtolower($request["name"]) . "." . $request->file("file")->getClientOriginalExtension();

        return response()->json([
            "filename" => $filename,
        ]);
    }
}
