<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * @property int $id
     * @property string $name
     * @property string $alternative_name
     * @property string $latitude
     * @property string $longitude
     * @property string $iso
     */
    protected $fillable = [
        "name",
        "alternative_name",
        "latitude",
        "longitude",
        "iso",
    ];
}
