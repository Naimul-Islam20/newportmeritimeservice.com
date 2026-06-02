<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WhereWeAreLocation;

class WhereWeAreLocationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, WhereWeAreLocation $whereWeAreLocation): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, WhereWeAreLocation $whereWeAreLocation): bool
    {
        return $user->isAdmin();
    }
}
