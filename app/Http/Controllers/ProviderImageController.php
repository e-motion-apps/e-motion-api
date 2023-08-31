<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ProviderImageController extends Controller
{
    public function __invoke(string $filename): Response
    {
        $imagePath = storage_path("app/public/providers/" . $filename);

        if (!file_exists($imagePath)) {
            $imagePath =  storage_path("app/public/providers/unknown.png");
        }

        return response(file_get_contents($imagePath), 200, ["Content-Type" => "image/png"]);
    }
}
