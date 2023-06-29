<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $name
 */
class CityAlternativeName extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "city_id",
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
