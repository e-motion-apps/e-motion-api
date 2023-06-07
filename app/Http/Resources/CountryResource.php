<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "alternativeName" => $this->alternative_name,
            "latitude" => $this->latitude,
            "longitude" => $this->longitude,
            "iso" => $this->iso,
        ];
    }
}
