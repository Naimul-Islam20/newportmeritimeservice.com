<?php

namespace App\Policies;

use App\Models\HomeSection;
use App\Models\User;

class HomeSectionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, HomeSection $homeSection): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, HomeSection $homeSection): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, HomeSection $homeSection): bool
    {
        return $user->isAdmin();
    }
}
