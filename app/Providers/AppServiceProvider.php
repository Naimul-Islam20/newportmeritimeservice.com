<?php

namespace App\Providers;

use App\Models\ContactMessage;
use App\Models\ExpertSession;
use App\Models\Newsletter;
use App\Models\NewsletterCategory;
use App\Models\QuoteRequest;
use App\Models\User;
use App\Policies\ContactMessagePolicy;
use App\Policies\ExpertSessionPolicy;
use App\Policies\NewsletterPolicy;
use App\Policies\NewsletterCategoryPolicy;
use App\Policies\QuoteRequestPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(ContactMessage::class, ContactMessagePolicy::class);
        Gate::policy(QuoteRequest::class, QuoteRequestPolicy::class);
        Gate::policy(ExpertSession::class, ExpertSessionPolicy::class);
        Gate::policy(Newsletter::class, NewsletterPolicy::class);
        Gate::policy(NewsletterCategory::class, NewsletterCategoryPolicy::class);
    }
}
