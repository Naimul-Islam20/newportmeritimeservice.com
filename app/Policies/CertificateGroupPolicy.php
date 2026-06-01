<?php

namespace App\Policies;

use App\Models\CertificateGroup;
use App\Models\User;

class CertificateGroupPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, CertificateGroup $certificateGroup): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, CertificateGroup $certificateGroup): bool
    {
        return $user->isAdmin();
    }
}
