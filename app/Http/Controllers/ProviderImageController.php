<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class ProviderImageController extends Controller
{
    public function __invoke(string $filename): Response
    {
        return response(file_get_contents(storage_path("app/public/providers/" . $filename)), 200, ["Content-Type" => "image/png"]);
    }
}
