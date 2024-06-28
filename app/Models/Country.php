<?php

declare(strict_types=1);

namespace App\Models;

use App\QueryBuilders\SortQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

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

    protected static function booted(): void
    {
        static::creating(function ($country): void {
            $country->slug = Str::slug($country->name);
        });
    }
}
