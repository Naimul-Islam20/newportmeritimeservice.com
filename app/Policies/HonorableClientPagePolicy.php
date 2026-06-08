<?php

namespace App\Policies;

use App\Models\HonorableClientPage;
use App\Models\User;

class HonorableClientPagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, HonorableClientPage $honorableClientPage): bool
    {
        return $user->isAdmin();
    }
}
