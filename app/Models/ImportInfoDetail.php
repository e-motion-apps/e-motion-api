<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportInfoDetail extends Model
{

    protected $fillable = [
      "provider_id",
      "import_id",
      "code",
    ];
    public function importInfo()
    {
        return $this->belongsTo(ImportInfo::class);
    }

    public function code()
    {
        return $this->hasMany(Code::class);
    }
}
