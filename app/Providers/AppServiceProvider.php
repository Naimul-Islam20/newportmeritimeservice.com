<?php

namespace App\Providers;

use App\Models\ContactMessage;
use App\Models\HeroSlide;
use App\Models\Menu;
use App\Models\SubMenu;
use App\Models\User;
use App\Policies\ContactMessagePolicy;
use App\Policies\HeroSlidePolicy;
use App\Policies\MenuPolicy;
use App\Policies\SubMenuPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
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
        Gate::policy(HeroSlide::class, HeroSlidePolicy::class);
        Gate::policy(Menu::class, MenuPolicy::class);
        Gate::policy(SubMenu::class, SubMenuPolicy::class);

        View::composer('site.partials.header', function ($view): void {
            $view->with('headerMenus', Menu::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->with(['subMenus' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')->orderBy('id')])
                ->get());
        });
    }
}
