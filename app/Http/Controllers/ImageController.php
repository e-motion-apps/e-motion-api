<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;
use Illuminate\Http\JsonResponse;

class ImageController extends Controller
{
    public function upload(ImageRequest $request, $imageName): JsonResponse
    {
        $image = $request->file('image');

        if ($image->move(public_path('providers'), $imageName)) {
            return response()->json(data: ['success' => true]);
        } else {
            return response()->json(data: ['success' => false]);
        }
    }
}
