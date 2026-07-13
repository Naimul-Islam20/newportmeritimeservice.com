<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Menu extends Model
{
    protected $fillable = [
        'label',
        'url',
        'description',
        'page_content',
        'cover_image_path',
        'sort_order',
        'is_active',
        'show_submenus_on_page',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'show_submenus_on_page' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function coverImageUrl(): string
    {
        return \App\Support\PublicUploadUrl::fromPath($this->cover_image_path);
    }

    public function subMenus(): HasMany
    {
        return $this->hasMany(SubMenu::class, 'menu_id');
    }

    /** Top-level submenu items (excludes nested children). */
    public function topLevelSubMenus(): HasMany
    {
        return $this->hasMany(SubMenu::class, 'menu_id')->whereNull('parent_sub_menu_id');
    }

    public function hasFlyoutSubMenus(): bool
    {
        $subs = $this->relationLoaded('subMenus') ? $this->subMenus : $this->subMenus()->get();

        return $subs->contains(fn (SubMenu $s) => $s->hasChildren());
    }

    public function pageSections(): MorphMany
    {
        return $this->morphMany(MenuPageSection::class, 'sectionable');
    }

    /**
     * Active submenus for the parent menu public page (grid, paginated), or null when the feature is off or empty.
     */
    public function submenuPaginatorForPublicParentPage(): ?LengthAwarePaginator
    {
        if (! $this->show_submenus_on_page) {
            return null;
        }

        if (! $this->subMenus()->where('is_active', true)->exists()) {
            return null;
        }

        return $this->subMenus()
            ->where('is_active', true)
            ->ordered()
            ->paginate(9)
            ->withQueryString();
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Site paths that are dedicated form pages (not CMS menu pages).
     *
     * @return list<string>
     */
    public static function formPagePaths(): array
    {
        return ['/contact', '/get-a-quote'];
    }

    public function isFormPageMenu(): bool
    {
        $p = $this->normalizedPath();

        return $p !== null && in_array($p, self::formPagePaths(), true);
    }

    public function isQuoteNavItem(): bool
    {
        return $this->normalizedPath() === '/get-a-quote';
    }

    /** Parent items that only open a submenu — no landing-page link on click. */
    public function isDropdownOnlyParentNav(): bool
    {
        $path = $this->normalizedPath();

        return in_array($path, ['/ship-supply', '/our-services', '/award'], true);
    }

    /** Public navigation URL (uses named routes for contact / quote forms). */
    public function siteNavHref(): string
    {
        $path = $this->normalizedPath();
        if ($path === '/contact') {
            return route('contact.create');
        }
        if ($path === '/get-a-quote') {
            return route('quote.request');
        }
        if ($path === '/locations') {
            return route('locations');
        }
        if ($path === '/career') {
            return route('career');
        }
        if ($path === '/award') {
            return route('award');
        }

        return $this->resolvedHref();
    }

    public function resolvedHref(): string
    {
        $raw = trim($this->url);

        if ($raw === '') {
            return '#';
        }

        if (preg_match('#^https?://#i', $raw)) {
            return $raw;
        }

        return str_starts_with($raw, '/') ? $raw : '/'.$raw;
    }

    /**
     * Normalized site path for matching the current request (leading slash, no trailing slash except root).
     */
    public function normalizedPath(): ?string
    {
        $raw = trim($this->url);

        if ($raw === '' || $raw === '#') {
            return null;
        }

        if (preg_match('#^https?://#i', $raw)) {
            $path = parse_url($raw, PHP_URL_PATH);

            if ($path === null || $path === '') {
                return '/';
            }

            $path = '/'.ltrim($path, '/');

            return rtrim($path, '/') === '' ? '/' : rtrim($path, '/');
        }

        $path = str_starts_with($raw, '/') ? $raw : '/'.$raw;
        $path = '/'.ltrim($path, '/');

        return rtrim($path, '/') === '' ? '/' : rtrim($path, '/');
    }

    public function isCurrent(): bool
    {
        $mine = $this->normalizedPath();

        if ($mine === null) {
            return false;
        }

        $current = request()->path();
        $currentPath = $current === '' ? '/' : '/'.ltrim($current, '/');
        $currentPath = rtrim($currentPath, '/') === '' ? '/' : rtrim($currentPath, '/');

        return $currentPath === $mine;
    }

    public function isActiveBranch(): bool
    {
        if ($this->isCurrent()) {
            return true;
        }

        foreach ($this->subMenus as $sub) {
            if ($sub->isCurrent()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Parent menu for a nested path (e.g. /our-services/foo → Our Services menu).
     */
    public static function findParentMenuForPath(string $path): ?self
    {
        $normalized = rtrim('/'.ltrim(trim($path), '/'), '/') ?: '/';

        return self::query()
            ->active()
            ->get()
            ->filter(fn (self $menu) => filled($menu->normalizedPath()) && $menu->normalizedPath() !== '/')
            ->sortByDesc(fn (self $menu) => strlen((string) $menu->normalizedPath()))
            ->first(function (self $menu) use ($normalized): bool {
                $menuPath = $menu->normalizedPath();

                return $menuPath !== null && str_starts_with($normalized, $menuPath.'/');
            });
    }

    /**
     * Breadcrumb trail for a top-level menu landing page.
     *
     * @return list<array{label: string, url?: string}>
     */
    public function heroBreadcrumbs(): array
    {
        return [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => $this->label],
        ];
    }

    public function adminSidebarHref(): string
    {
        return match ($this->normalizedPath()) {
            '/career' => route('admin.career-page.edit'),
            '/ship-supply' => route('admin.ship-supply-sub-menus.index'),
            '/our-services' => route('admin.our-services-sub-menus.index'),
            '/contact' => route('admin.contact-messages.index'),
            default => route('admin.menus.page-sections.index', $this),
        };
    }

    public function adminSidebarIsActive(): bool
    {
        return match ($this->normalizedPath()) {
            '/career' => request()->routeIs('admin.career-page.*'),
            '/ship-supply' => request()->routeIs('admin.ship-supply-sub-menus.*')
                || request()->routeIs('admin.ship-supply-landing.*'),
            '/our-services' => request()->routeIs('admin.our-services-sub-menus.*')
                || request()->routeIs('admin.service-pages.*')
                || request()->routeIs('admin.service-sidebar.*'),
            '/contact' => request()->routeIs('admin.contact-messages.*'),
            default => (request()->routeIs('admin.menus.edit') || request()->routeIs('admin.menus.page-sections.*'))
                && request()->route('menu') instanceof self
                && (int) request()->route('menu')->id === (int) $this->id,
        };
    }

    /**
     * Primary "OUR SERVICES" menu for header dropdown and service page sidebars.
     */
    public static function ourServicesMenu(): ?self
    {
        return self::query()
            ->where('is_active', true)
            ->where(function (Builder $q): void {
                $q->where('url', '/our-services')
                    ->orWhere('url', 'our-services')
                    ->orWhereRaw('LOWER(label) LIKE ?', ['%our services%']);
            })
            ->with(['subMenus' => fn ($q) => $q->active()->ordered()])
            ->first();
    }

    /**
     * Our Services menu row for admin sub-menu management (includes inactive menu).
     */
    public static function ourServicesAdminMenu(): ?self
    {
        return self::query()
            ->where(function (Builder $q): void {
                $q->where('url', '/our-services')
                    ->orWhere('url', 'our-services')
                    ->orWhereRaw('LOWER(label) LIKE ?', ['%our services%']);
            })
            ->orderBy('sort_order')
            ->orderBy('id')
            ->first();
    }

    /**
     * Primary BLOG menu for blog category navigation (News / Events / Gallery / Recipes / TV).
     */
    public static function blogMenu(): ?self
    {
        $menu = self::query()
            ->where('is_active', true)
            ->where(function (Builder $q): void {
                $q->where('url', '/blog')
                    ->orWhere('url', 'blog')
                    ->orWhereRaw('LOWER(label) LIKE ?', ['%blog%']);
            })
            ->with([
                'subMenus' => fn ($query) => $query
                    ->active()
                    ->whereNull('parent_sub_menu_id')
                    ->ordered(),
            ])
            ->first();

        return self::withHeaderNavSubMenus($menu);
    }

    /**
     * Primary WHO WE ARE menu for header dropdown management.
     */
    public static function whoWeAreMenu(): ?self
    {
        $whoWeAre = self::query()
            ->whereRaw('LOWER(label) LIKE ?', ['%who we are%'])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->first();

        if ($whoWeAre) {
            return $whoWeAre;
        }

        return self::query()
            ->where('url', '/')
            ->whereHas('subMenus')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->first()
            ?? self::query()->where('url', '/')->orderBy('sort_order')->orderBy('id')->first();
    }

    /**
     * Primary SHIP SUPPLY menu for header dropdown management.
     */
    public static function shipSupplyMenu(): ?self
    {
        return self::query()
            ->where(function (Builder $q): void {
                $q->where('url', '/ship-supply')
                    ->orWhere('url', 'ship-supply')
                    ->orWhereRaw('LOWER(label) LIKE ?', ['%ship supply%']);
            })
            ->orderBy('sort_order')
            ->orderBy('id')
            ->first();
    }

    /**
     * Primary AWARD menu for header dropdown management.
     */
    public static function awardMenu(): ?self
    {
        return self::query()
            ->where(function (Builder $q): void {
                $q->where('url', '/award')
                    ->orWhere('url', 'award')
                    ->orWhereRaw('LOWER(label) LIKE ?', ['%award%']);
            })
            ->orderBy('sort_order')
            ->orderBy('id')
            ->first();
    }

    /**
     * Strip BLOG post links from submenu lists used in header / tabs.
     */
    public static function withHeaderNavSubMenus(?self $menu): ?self
    {
        if (! $menu) {
            return null;
        }

        $menu->setRelation(
            'subMenus',
            $menu->subMenus
                ->each(fn (SubMenu $sub) => $sub->setRelation('menu', $menu))
                ->filter(fn (SubMenu $sub) => $sub->showInSiteHeaderNav())
                ->values(),
        );

        return $menu;
    }
}
