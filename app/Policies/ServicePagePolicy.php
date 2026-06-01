<?php

namespace App\Policies;

use App\Models\ServicePage;
use App\Models\User;

class ServicePagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, ServicePage $servicePage): bool
    {
        return $user->isAdmin();
    }
}
