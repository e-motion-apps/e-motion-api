<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $name
 * @property string $alternative_name
 * @property string $latitude
 * @property string $longitude
 * @property string $iso
 */
class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "alternative_name",
        "latitude",
        "longitude",
        "iso",
    ];

    public function city(): HasOne
    {
        return $this->hasOne(City::class);
    }
}
