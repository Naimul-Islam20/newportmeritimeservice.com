<?php

namespace App\Policies;

use App\Models\CeoMessagePage;
use App\Models\User;

class CeoMessagePagePolicy
{
    public function update(User $user, CeoMessagePage $ceoMessagePage): bool
    {
        return $user->isAdmin();
    }
}
