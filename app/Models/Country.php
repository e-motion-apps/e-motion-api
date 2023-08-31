<?php

declare(strict_types=1);

namespace App\Models;

use App\QueryBuilders\SortQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

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

    protected static function booted()
    {
        static::creating(function ($country) {
            $country->slug = Str::slug($country->name);
        });
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public static function query(): Builder
    {
        return parent::query();
    }

    public function newEloquentBuilder($query): SortQuery
    {
        return new SortQuery($query);
    }
}
