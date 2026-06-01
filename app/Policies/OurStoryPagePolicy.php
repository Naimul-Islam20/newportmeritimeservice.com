<?php

namespace App\Policies;

use App\Models\OurStoryPage;
use App\Models\User;

class OurStoryPagePolicy
{
    public function update(User $user, OurStoryPage $ourStoryPage): bool
    {
        return $user->isAdmin();
    }
}
