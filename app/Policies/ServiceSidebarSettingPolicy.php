<?php

namespace App\Policies;

use App\Models\ServiceSidebarSetting;
use App\Models\User;

class ServiceSidebarSettingPolicy
{
    public function update(User $user, ServiceSidebarSetting $serviceSidebarSetting): bool
    {
        return $user->isAdmin();
    }
}
