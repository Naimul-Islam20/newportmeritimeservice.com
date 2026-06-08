<?php

namespace App\Policies;

use App\Models\HonorableClient;
use App\Models\User;

class HonorableClientPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, HonorableClient $honorableClient): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, HonorableClient $honorableClient): bool
    {
        return $user->isAdmin();
    }
}
