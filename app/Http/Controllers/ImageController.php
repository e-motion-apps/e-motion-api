<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;
use Illuminate\Http\JsonResponse;

class ImageController extends Controller
{
    public function upload(ImageRequest $request, $imageName): JsonResponse
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            if ($image->move(public_path('providers'), $imageName)) {
                return response()->json(['success' => true, 'message' => 'Image uploaded successfully']);
            } else {
                return response()->json(['success' => false, 'message' => 'Failed to upload image'], 500);
            }
        }
        return response()->json(['success' => false, 'message' => 'No image file provided'], 400);
    }
}
