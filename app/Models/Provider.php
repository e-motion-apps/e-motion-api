<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $provider_id
 * @property int $city_id
 */
class Provider extends Model
{
    protected $fillable = [
        "provider_id",
        "city_id",
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function providerList()
    {
        return $this->hasMany(ProviderList::class);
    }
}
