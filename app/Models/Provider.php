<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $provider_list_id
 * @property int $city_id
 */
class Provider extends Model
{
    protected $fillable = [
        "provider_list_id",
        "city_id",
        "created_by",
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function providerList(): HasMany
    {
        return $this->hasMany(ProviderList::class);
    }
}
