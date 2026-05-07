<?php

namespace App\Policies;

use App\Models\SiteDetail;
use App\Models\User;

class SiteDetailPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, SiteDetail $siteDetail): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, SiteDetail $siteDetail): bool
    {
        return $user->isAdmin();
    }
}
