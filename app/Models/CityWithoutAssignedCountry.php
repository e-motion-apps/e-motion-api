<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CityWithoutAssignedCountry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "city_name",
        "country_name",
    ];
    protected $dates = ["deleted_at"];
}
