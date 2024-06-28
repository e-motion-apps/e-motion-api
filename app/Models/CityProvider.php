<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
