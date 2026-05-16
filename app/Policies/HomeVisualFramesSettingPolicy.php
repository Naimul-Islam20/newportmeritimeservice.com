<?php

namespace App\Policies;

use App\Models\HomeVisualFramesSetting;
use App\Models\User;

class HomeVisualFramesSettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, HomeVisualFramesSetting $homeVisualFramesSetting): bool
    {
        return $user->isAdmin();
    }
}
