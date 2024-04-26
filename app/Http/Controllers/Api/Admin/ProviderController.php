<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiProviderRequest;
use App\Http\Resources\ProviderResource;
use App\Models\Provider;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ProviderController extends Controller
{
    public function index(): JsonResponse
    {
        $providers = Provider::all();
        return response()->json([
            "providers" => ProviderResource::collection($providers),
        ]);
    }

    public function store(ApiProviderRequest $request): JsonResponse
    {
        $provider = Provider::create($request->validated());

        if ($request->has("file")) {
            if (!$this->processProviderImage($request->file, $provider->name)) {
                return response()->json(["message" => __("The image must be 150x100 pixels.")], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        return response()->json(["message" => __("Provider created successfully.")], Response::HTTP_CREATED);
    }

    public function update(ApiProviderRequest $request, Provider $provider): JsonResponse
    {
        $provider->update($request->validated());

        if ($request->has("file")) {
            $this->replaceProviderImage($request->file, $provider->name);
        }

        return response()->json(["message" => __("Provider updated successfully.")]);
    }

    public function destroy(Provider $provider): JsonResponse
    {
        $provider->delete();
        $imagePath = $this->providerImagePath($provider->name);
        File::delete($imagePath);

        return response()->json(["message" => __("Provider deleted successfully.")]);
    }

    public function showLogo(string $filename): JsonResponse
    {
        $imagePath = $this->providerImagePath($filename, true);

        $imageData = base64_encode(file_get_contents($imagePath));

        return response()->json([
            "image" => $imageData,
            "mime_type" => "image/png",
        ]);
    }

    private function providerImagePath(string $name, bool $useDefault = false): string
    {
        $path = storage_path("app/public/providers/" . strtolower($name) . ".png");

        return file_exists($path) || !$useDefault ? $path : storage_path("app/public/providers/unknown.png");
    }

    private function processProviderImage(string $encodedFile, string $name): bool
    {
        [$decodedFile, $mimeType] = $this->decodeFile($encodedFile);

        if (!$this->validateImageDimensions($decodedFile)) {
            return false;
        }

        Storage::disk("public")->put("providers/" . strtolower($name) . "." . $mimeType, $decodedFile);

        return true;
    }

    private function replaceProviderImage(string $encodedFile, string $name): void
    {
        $oldImagePath = $this->providerImagePath($name);
        Storage::disk("public")->delete($oldImagePath);
        $this->processProviderImage($encodedFile, $name);
    }

    private function decodeFile(string $encodedFile): array
    {
        preg_match('/^data:image\/(\w+);base64,/', $encodedFile, $matches);
        $decodedFile = base64_decode(explode(",", $encodedFile)[1] ?? "", true);

        return [$decodedFile, $matches[1] ?? "png"];
    }

    private function validateImageDimensions(string $decodedFile): bool
    {
        $imageResource = imagecreatefromstring($decodedFile);
        $width = imagesx($imageResource);
        $height = imagesy($imageResource);
        imagedestroy($imageResource);

        return $width === 150 && $height === 100;
    }
}
