<?php

namespace App\Policies;

use App\Models\OurTeamPage;
use App\Models\User;

class OurTeamPagePolicy
{
    public function update(User $user, OurTeamPage $ourTeamPage): bool
    {
        return $user->isAdmin();
    }
}
