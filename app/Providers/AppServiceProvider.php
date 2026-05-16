<?php

namespace App\Providers;

use App\Models\AboutPage;
use App\Models\ContactMessage;
use App\Models\HeroSlide;
use App\Models\HomeSection;
use App\Models\HomeServiceAreaSetting;
use App\Models\HomeVisualFramesSetting;
use App\Models\Menu;
use App\Models\QuoteRequest;
use App\Models\SiteDetail;
use App\Models\SubMenu;
use App\Models\User;
use App\Policies\AboutPagePolicy;
use App\Policies\ContactMessagePolicy;
use App\Policies\HeroSlidePolicy;
use App\Policies\HomeSectionPolicy;
use App\Policies\HomeServiceAreaSettingPolicy;
use App\Policies\HomeVisualFramesSettingPolicy;
use App\Policies\MenuPolicy;
use App\Policies\QuoteRequestPolicy;
use App\Policies\SiteDetailPolicy;
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
        Gate::policy(AboutPage::class, AboutPagePolicy::class);
        Gate::policy(ContactMessage::class, ContactMessagePolicy::class);
        Gate::policy(HeroSlide::class, HeroSlidePolicy::class);
        Gate::policy(HomeSection::class, HomeSectionPolicy::class);
        Gate::policy(HomeServiceAreaSetting::class, HomeServiceAreaSettingPolicy::class);
        Gate::policy(HomeVisualFramesSetting::class, HomeVisualFramesSettingPolicy::class);
        Gate::policy(Menu::class, MenuPolicy::class);
        Gate::policy(QuoteRequest::class, QuoteRequestPolicy::class);
        Gate::policy(SiteDetail::class, SiteDetailPolicy::class);
        Gate::policy(SubMenu::class, SubMenuPolicy::class);

        View::composer('site.partials.header', function ($view): void {
            $view->with('headerMenus', Menu::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->with(['subMenus' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')->orderBy('id')])
                ->get());
            $view->with('siteDetails', SiteDetail::query()->first());
        });

        View::composer('site.partials.footer', function ($view): void {
            $view->with('siteDetails', SiteDetail::query()->first());
        });

        View::composer([
            'site.layouts.app',
            'layouts.admin',
            'admin.auth.login',
        ], function ($view): void {
            $detail = SiteDetail::query()->first();
            $view->with(
                'siteThemeCssVars',
                $detail ? $detail->themeVariablesForCss() : (new SiteDetail)->themeVariablesForCss(),
            );
            $view->with('adminHeaderLogoUrl', SiteDetail::headerLogoAssetUrl($detail));
        });

        View::composer('layouts.admin', function ($view): void {
            $menus = Menu::query()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->with(['subMenus' => fn ($q) => $q->orderBy('sort_order')->orderBy('id')])
                ->get()
                ->map(function (Menu $menu): ?Menu {
                    if ($menu->isFormPageMenu()) {
                        return null;
                    }
                    if ($menu->normalizedPath() === '/about-us') {
                        return null;
                    }
                    if ($menu->normalizedPath() === '/') {
                        return null;
                    }
                    $subs = $menu->subMenus
                        ->filter(fn (SubMenu $s) => ! $s->isFormPageLink() && $s->normalizedPath() !== '/')
                        ->values();
                    $menu->setRelation('subMenus', $subs);

                    return $menu;
                })
                ->filter()
                ->values();

            $view->with('adminSidebarMenus', $menus);
        });
    }
}
