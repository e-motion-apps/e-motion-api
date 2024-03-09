<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\CityOpinion;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CityOpinionPolicy
{
    public function update(User $user, CityOpinion $cityOpinion): Response
    {
        if ($cityOpinion->user_id === $user->id) {
            return Response::allow();
        }

        return Response::denyWithStatus(403, "You do not own this opinion");
    }

    public function delete(User $user, CityOpinion $cityOpinion): Response
    {
        if ($cityOpinion->user_id === $user->id || $user->hasRole("admin")) {
            return Response::allow();
        }

        return Response::denyWithStatus(403, "You do not own this opinion");
    }
}
