<?php

namespace App\Policies;

use App\Models\AboutPage;
use App\Models\User;

class AboutPagePolicy
{
    public function view(User $user, AboutPage $aboutPage): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, AboutPage $aboutPage): bool
    {
        return $user->isAdmin();
    }
}
