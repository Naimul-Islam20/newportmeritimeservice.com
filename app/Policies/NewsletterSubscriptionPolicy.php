<?php

namespace App\Policies;

use App\Models\NewsletterSubscription;
use App\Models\User;

class NewsletterSubscriptionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, NewsletterSubscription $newsletterSubscription): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, NewsletterSubscription $newsletterSubscription): bool
    {
        return $user->isAdmin();
    }
}
