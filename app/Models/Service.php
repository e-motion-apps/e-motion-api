<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $type
 */

class Service extends Model
{
    protected $fillable = [
        "type",
    ];
}
