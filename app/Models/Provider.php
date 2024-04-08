<?php

declare(strict_types=1);

namespace App\Models;

use App\QueryBuilders\SortQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $url
 * @property string $color
 */
class Provider extends Model
{
    public $incrementing = false;
    protected $primaryKey = "name";
    protected $keyType = "string";
    protected $fillable = [
        "name",
        "url",
        "color",
    ];

    public static function query(): Builder
    {
        return parent::query();
    }

    public function newEloquentBuilder($query): SortQuery
    {
        return new SortQuery($query);
    }
}
