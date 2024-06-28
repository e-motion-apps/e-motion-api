<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;

    protected $fillable = [
        "name",
        "email",
        "password",
    ];

    protected $hidden = [
        "password",
        "remember_token",
        "role" => "user",
    ];

    protected $casts = [
        "email_verified_at" => "datetime",
        "password" => "hashed",
    ];

    public function isAdmin(): bool
    {
        return $this->hasRole("admin");
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorites::class);
    }

    public function cityOpinions(): HasMany
    {
        return $this->hasMany(CityOpinion::class);
    }
}
