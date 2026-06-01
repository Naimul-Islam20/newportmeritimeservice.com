<?php

namespace App\Policies;

use App\Models\QualityCertificate;
use App\Models\User;

class QualityCertificatePolicy
{
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, QualityCertificate $qualityCertificate): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, QualityCertificate $qualityCertificate): bool
    {
        return $user->isAdmin();
    }
}
