<?php

namespace App\Policies;

use App\Models\ExpertSession;
use App\Models\User;

class ExpertSessionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, ExpertSession $expertSession): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, ExpertSession $expertSession): bool
    {
        return $user->isAdmin();
    }
}
