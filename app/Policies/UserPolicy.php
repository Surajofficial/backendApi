<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function manageUsers(User $user): bool
    {
        return $user->role === 'admin';
    }
}
