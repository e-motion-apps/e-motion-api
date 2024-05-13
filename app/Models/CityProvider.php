<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $provider_name
 * @property int $city_id
 * @property string $created_by
 * @property int $service_id
 */
class CityProvider extends Model
{
    protected $fillable = [
        "provider_name",
        "city_id",
        "created_by",
        "service_id",
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function providers(): HasMany
    {
        return $this->hasMany(Provider::class);
    }

    public function services(): HasOne
    {
        return $this->hasOne(Service::class);
    }
}
