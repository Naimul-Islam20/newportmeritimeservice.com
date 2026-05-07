<?php

namespace App\Policies;

use App\Models\SubMenu;
use App\Models\User;

class SubMenuPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, SubMenu $subMenu): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, SubMenu $subMenu): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, SubMenu $subMenu): bool
    {
        return $user->isAdmin();
    }
}
