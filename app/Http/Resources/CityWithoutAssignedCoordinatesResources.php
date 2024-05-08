<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityWithoutAssignedCoordinatesResources extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "city_name" => $this->city_name,
            "country_name" => $this->country_name,
            "latitude" => $this->latitude,
            "longitude" => $this->longitude,
        ];
    }
}
