<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\CityOpinion;
use App\Models\User;

class CityOpinionPolicy
{
    public function update(User $user, CityOpinion $cityOpinion): bool
    {
        return $cityOpinion->user_id === $user->id;
    }

    public function delete(User $user, CityOpinion $cityOpinion): bool
    {
        return $cityOpinion->user_id === $user->id || $user->hasRole("admin");
    }
}
