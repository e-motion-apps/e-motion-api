<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;

class RolesController
{
    public function assignRole(User $user)
    {
        $user->assignRole('admin');
    }

    public function revokeRole(User $user)
    {
        $user->removeRole('admin');
    }
}
