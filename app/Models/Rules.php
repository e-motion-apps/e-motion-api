<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rules extends Model
{
    use HasFactory;

    protected $table = "rules_for_cities";
    protected $fillable = [
        "city_id",
        "country_id",
        "rules_en",
        "rules_pl",
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
