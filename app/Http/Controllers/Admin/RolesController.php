<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;

class RolesController
{
    public function assignAdmin(User $user)
    {
        $user->assignRole('admin');
    }

    public function revokeAdmin(User $user)
    {
        $user->removeRole('admin');
    }
}
