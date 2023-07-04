<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $primaryKey = "number";
    protected $fillable = [
        "number",
        "description",
    ];

    public function importInfoDetail()
    {
        return $this->belongsTo(ImportInfoDetail::class);
    }
}
