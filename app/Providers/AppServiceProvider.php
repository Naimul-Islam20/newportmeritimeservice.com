<?php

namespace App\Providers;

use App\Models\AboutPage;
use App\Models\CareerPage;
use App\Models\WhereWeAreLocation;
use App\Models\CeoMessagePage;
use App\Models\CertificateGroup;
use App\Models\CertificatePage;
use App\Models\ContactMessage;
use App\Models\HeroSlide;
use App\Models\HomeSection;
use App\Models\HomeServiceAreaSetting;
use App\Models\HomeVisualFramesSetting;
use App\Models\Menu;
use App\Models\OurStoryPage;
use App\Models\OurTeamPage;
use App\Models\QualityCertificate;
use App\Models\QuoteRequest;
use App\Models\ServicePage;
use App\Models\ServiceSidebarSetting;
use App\Models\SiteDetail;
use App\Models\SubMenu;
use App\Models\User;
use App\Policies\AboutPagePolicy;
use App\Policies\CareerPagePolicy;
use App\Policies\WhereWeAreLocationPolicy;
use App\Policies\CeoMessagePagePolicy;
use App\Policies\CertificateGroupPolicy;
use App\Policies\CertificatePagePolicy;
use App\Policies\ContactMessagePolicy;
use App\Policies\HeroSlidePolicy;
use App\Policies\HomeSectionPolicy;
use App\Policies\HomeServiceAreaSettingPolicy;
use App\Policies\HomeVisualFramesSettingPolicy;
use App\Policies\MenuPolicy;
use App\Policies\OurStoryPagePolicy;
use App\Policies\OurTeamPagePolicy;
use App\Policies\QualityCertificatePolicy;
use App\Policies\ServicePagePolicy;
use App\Policies\ServiceSidebarSettingPolicy;
use App\Policies\SiteDetailPolicy;
use App\Policies\SubMenuPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $helpers = app_path('helpers.php');
        if (is_file($helpers)) {
            require_once $helpers;
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('local') && ! $this->app->runningInConsole()) {
            $root = request()->getSchemeAndHttpHost();
            if (is_string($root) && $root !== '') {
                URL::forceRootUrl($root);
            }
        }

        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(CertificatePage::class, CertificatePagePolicy::class);
        Gate::policy(CertificateGroup::class, CertificateGroupPolicy::class);
        Gate::policy(QualityCertificate::class, QualityCertificatePolicy::class);
        Gate::policy(AboutPage::class, AboutPagePolicy::class);
        Gate::policy(CareerPage::class, CareerPagePolicy::class);
        Gate::policy(WhereWeAreLocation::class, WhereWeAreLocationPolicy::class);
        Gate::policy(OurStoryPage::class, OurStoryPagePolicy::class);
        Gate::policy(CeoMessagePage::class, CeoMessagePagePolicy::class);
        Gate::policy(OurTeamPage::class, OurTeamPagePolicy::class);
        Gate::policy(ServicePage::class, ServicePagePolicy::class);
        Gate::policy(ServiceSidebarSetting::class, ServiceSidebarSettingPolicy::class);
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
                // Hidden from navbar (remove a path to show again)
                ->where(function ($q): void {
                    $q->whereNotIn('url', ['/ship-supply', 'ship-supply', '/award', 'award'])
                        ->whereRaw('LOWER(label) NOT LIKE ?', ['%ship supply%'])
                        ->whereRaw('LOWER(label) NOT LIKE ?', ['%award%']);
                })
                ->orderBy('sort_order')
                ->orderBy('id')
                ->with([
                    'subMenus' => fn ($q) => $q
                        ->whereNull('parent_sub_menu_id')
                        ->where('is_active', true)
                        ->orderBy('sort_order')
                        ->orderBy('id')
                        ->with([
                            'children' => fn ($c) => $c
                                ->where('is_active', true)
                                ->orderBy('sort_order')
                                ->orderBy('id'),
                        ]),
                ])
                ->get());
            $view->with('siteDetails', SiteDetail::query()->first());
        });

        View::composer('site.partials.footer', function ($view): void {
            $view->with('siteDetails', SiteDetail::query()->first());
            $view->with('footerMenus', Menu::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get());
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
            $view->with(SiteDetail::metaForViews($detail));
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
                    $subs = $menu->subMenus
                        ->filter(fn (SubMenu $s) => ! $s->isFormPageLink())
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
