<?php

namespace App\Policies;

use App\Models\HomeServiceAreaSetting;
use App\Models\User;

class HomeServiceAreaSettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, HomeServiceAreaSetting $homeServiceAreaSetting): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, HomeServiceAreaSetting $homeServiceAreaSetting): bool
    {
        return $user->isAdmin();
    }
}
