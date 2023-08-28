<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;

class ImageController extends Controller
{
    public function upload(ImageRequest $request, $imageName): void
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $image->move(public_path('providers'), $imageName);
        }
    }
}
