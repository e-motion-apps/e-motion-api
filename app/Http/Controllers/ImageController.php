<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;

class ImageController extends Controller
{
    public function upload(ImageRequest $request, $imageName): JsonResponse
    {
        $image = $request->file("image");

        if ($image->move(public_path("providers"), $imageName)) {
            return response()->json(data: ["success" => true]);
        }

        return response()->json(data: ["success" => false]);
    }

    public function destroy($imageName): void
    {
        $filePath = public_path("providers/" . $imageName);
        File::delete($filePath);
    }
}
