<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportInfo extends Model
{
    protected $fillable = [
        "who_runs_it",
        "status",
    ];

    public function importInfoDetails()
    {
        return $this->hasMany(ImportInfoDetail::class);
    }
}
