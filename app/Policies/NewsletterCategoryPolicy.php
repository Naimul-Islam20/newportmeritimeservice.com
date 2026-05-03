<?php

namespace App\Policies;

use App\Models\NewsletterCategory;
use App\Models\User;

class NewsletterCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, NewsletterCategory $newsletterCategory): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, NewsletterCategory $newsletterCategory): bool
    {
        return $user->isAdmin();
    }
}
