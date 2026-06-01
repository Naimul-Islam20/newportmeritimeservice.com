<?php

namespace App\Policies;

use App\Models\CareerPage;
use App\Models\User;

class CareerPagePolicy
{
    public function update(User $user, CareerPage $careerPage): bool
    {
        return $user->isAdmin();
    }
}
