<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $url
 * @property string $color
 */
class ProviderList extends Model
{
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
