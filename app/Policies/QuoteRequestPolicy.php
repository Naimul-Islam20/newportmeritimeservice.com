<?php

namespace App\Policies;

use App\Models\QuoteRequest;
use App\Models\User;

class QuoteRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, QuoteRequest $quoteRequest): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, QuoteRequest $quoteRequest): bool
    {
        return $user->isAdmin();
    }
}
