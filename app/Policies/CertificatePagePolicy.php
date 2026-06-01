<?php

namespace App\Policies;

use App\Models\CertificatePage;
use App\Models\User;

class CertificatePagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, CertificatePage $certificatePage): bool
    {
        return $user->isAdmin();
    }
}
