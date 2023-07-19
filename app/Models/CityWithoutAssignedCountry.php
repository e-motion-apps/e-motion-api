<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CityWithoutAssignedCountry extends Model
{
    protected $fillable = [
        "city_name",
        "country_name",
    ];
}
