<?php

namespace App\Models;

use App\Support\PublicUploadUrl;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SubMenu extends Model
{
    protected $table = 'sub_menus';

    protected $fillable = [
        'menu_id',
        'parent_sub_menu_id',
        'label',
        'url',
        'description',
        'page_content',
        'cover_image_path',
        'icon_image_path',
        'published_at',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'published_at' => 'date',
        ];
    }

    public function coverImageUrl(): string
    {
        return PublicUploadUrl::fromPath($this->cover_image_path);
    }

    public function iconImageUrl(): string
    {
        return PublicUploadUrl::fromPath($this->icon_image_path);
    }

    private const DEFAULT_PAGE_HERO = 'https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=2070&auto=format&fit=crop';

    /**
     * Default menu/submenu page hero background (uploaded site cover, else port stock image).
     */
    public static function defaultPageHeroBackgroundUrl(): string
    {
        $uploaded = PublicUploadUrl::fromPath('menu-page-cover.jpg');

        if ($uploaded !== '') {
            return $uploaded;
        }

        if (is_file(public_path('menu-page-cover.jpg'))) {
            return asset('menu-page-cover.jpg');
        }

        return self::DEFAULT_PAGE_HERO;
    }

    /**
     * Same background URL as the submenu page hero (cover upload, else default cover asset).
     */
    public function pageHeroBackgroundUrl(): string
    {
        $cover = $this->coverImageUrl();

        if ($cover !== '') {
            return $cover;
        }

        return self::defaultPageHeroBackgroundUrl();
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_sub_menu_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_sub_menu_id');
    }

    public function hasChildren(): bool
    {
        if ($this->relationLoaded('children')) {
            return $this->children->isNotEmpty();
        }

        return $this->children()->where('is_active', true)->exists();
    }

    public function pageSections(): MorphMany
    {
        return $this->morphMany(MenuPageSection::class, 'sectionable');
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

    public function isFormPageLink(): bool
    {
        $p = $this->normalizedPath();

        return $p !== null && in_array($p, Menu::formPagePaths(), true);
    }

    public function isQuoteNavItem(): bool
    {
        return $this->normalizedPath() === '/get-a-quote';
    }

    public function isCertificationsMembershipNavItem(): bool
    {
        return $this->normalizedPath() === '/quality-certificates-memberships';
    }

    public function isHonorableClientNavItem(): bool
    {
        return $this->normalizedPath() === '/award/honorable-client';
    }

    /** Submenu parents that only open a flyout — no landing-page link on click. */
    public function isDropdownOnlyParentNav(): bool
    {
        return $this->normalizedPath() === '/where-we-are';
    }

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
        if ($path !== null && preg_match('#^/where-we-are/([^/]+)$#', $path, $m)) {
            return route('where-we-are.location', $m[1]);
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

    public static function isBlogMenu(Menu $menu): bool
    {
        $path = $menu->normalizedPath();

        return $path === '/blog';
    }

    /**
     * Top-level submenu under BLOG (News, Events, Gallery, …) — each manages its own items.
     */
    public function isNavDropdownCategory(): bool
    {
        if ($this->parent_sub_menu_id !== null) {
            return false;
        }

        $menu = $this->relationLoaded('menu') ? $this->menu : $this->menu()->first();

        if (! $menu || ! self::isBlogMenu($menu)) {
            return false;
        }

        $path = $this->normalizedPath();

        return $path !== null
            && $path !== '/blog'
            && preg_match('#^/blog/[^/]+$#', $path) === 1;
    }

    public function isBlogCategory(): bool
    {
        return $this->isNavDropdownCategory();
    }

    public function categoryBasePath(): ?string
    {
        return $this->isNavDropdownCategory() ? $this->normalizedPath() : null;
    }

    public function categoryCreateButtonLabel(): string
    {
        return 'Create '.$this->label;
    }

    /**
     * Frontend layout for BLOG categories (Gimaş-style).
     *
     * @return 'sidebar_article'|'sidebar_content'|'recipes'|'gallery'
     */
    public function blogLayoutType(): string
    {
        return match ($this->categoryBasePath()) {
            '/blog/news', '/blog/newport-tv' => 'sidebar_article',
            '/blog/events' => 'sidebar_content',
            '/blog/recipes' => 'recipes',
            '/blog/gallery' => 'gallery',
            default => 'sidebar_content',
        };
    }

    public function usesBlogSidebar(): bool
    {
        return in_array($this->blogLayoutType(), ['sidebar_article', 'sidebar_content'], true);
    }

    public function isBlogCategoryPost(): bool
    {
        if ($this->isNavDropdownCategory()) {
            return false;
        }

        $menu = $this->relationLoaded('menu') ? $this->menu : $this->menu()->first();

        if (! $menu || ! self::isBlogMenu($menu)) {
            return false;
        }

        if ($this->parent_sub_menu_id) {
            $parent = $this->relationLoaded('parent') ? $this->parent : $this->parent()->first();

            return $parent?->isNavDropdownCategory() === true;
        }

        return self::resolveCategoryFromPostPath($menu, (string) $this->url) !== null;
    }

    public function blogCategoryForPost(): ?self
    {
        if ($this->isNavDropdownCategory()) {
            return $this;
        }

        $parent = $this->relationLoaded('parent') ? $this->parent : $this->parent()->first();
        if ($parent?->isNavDropdownCategory()) {
            return $parent;
        }

        $menu = $this->relationLoaded('menu') ? $this->menu : $this->menu()->first();

        return $menu ? self::resolveCategoryFromPostPath($menu, (string) $this->url) : null;
    }

    /**
     * Fix legacy posts saved as /blog/slug instead of /blog/news/slug.
     */
    public function repairBlogPostUrlAndParent(): void
    {
        $category = $this->blogCategoryForPost();
        if (! $category || $this->isNavDropdownCategory()) {
            return;
        }

        $path = $this->normalizedPath();
        $base = $category->categoryBasePath();
        $slug = Str::slug($this->label) ?: 'item';

        if ($base && $path !== null && ! preg_match('#^'.preg_quote($base, '#').'/[^/]+$#', $path)) {
            $candidate = rtrim($base, '/').'/'.$slug;
            $i = 2;
            while (self::query()->where('url', $candidate)->where('id', '!=', $this->id)->exists()) {
                $candidate = rtrim($base, '/').'/'.$slug.'-'.$i;
                $i++;
            }
            $this->url = $candidate;
        }

        if ((int) $this->parent_sub_menu_id !== (int) $category->id) {
            $this->parent_sub_menu_id = $category->id;
        }

        if ($this->isDirty()) {
            $this->save();
        }
    }

    public function suggestCategoryPostUrl(string $slug): string
    {
        $base = $this->categoryBasePath() ?? '/blog/item';
        $slug = trim($slug, '/');

        return $slug === '' ? $base : rtrim($base, '/').'/'.$slug;
    }

    public static function pathFromUrl(string $url): ?string
    {
        $raw = trim($url);

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

    /**
     * Items that belong only to this menu (News, Events, …) — not mixed with other categories.
     *
     * @return \Illuminate\Support\Collection<int, self>
     */
    public function categoryItems(bool $repairOrphans = false): \Illuminate\Support\Collection
    {
        $base = $this->categoryBasePath();

        if ($base === null) {
            return collect();
        }

        $pattern = '#^'.preg_quote($base, '#').'/[^/]+$#';

        $orphans = self::query()
            ->where('menu_id', $this->menu_id)
            ->where('id', '!=', $this->id)
            ->active()
            ->ordered()
            ->get()
            ->filter(function (self $post) use ($pattern): bool {
                $path = $post->normalizedPath();

                return $path !== null
                    && preg_match($pattern, $path) === 1
                    && (int) $post->parent_sub_menu_id !== (int) $this->id;
            });

        if ($repairOrphans) {
            foreach ($orphans as $post) {
                $post->parent_sub_menu_id = $this->id;
                $post->repairBlogPostUrlAndParent();
            }

            self::query()
                ->where('parent_sub_menu_id', $this->id)
                ->each(function (self $post): void {
                    $post->repairBlogPostUrlAndParent();
                });
        }

        return $this->children()->active()->ordered()->get();
    }

    public function blogCategoryPosts(bool $repairOrphans = false): \Illuminate\Support\Collection
    {
        return $this->categoryItems($repairOrphans);
    }

    public static function resolveCategoryFromPostPath(Menu $menu, string $url): ?self
    {
        if (! self::isBlogMenu($menu)) {
            return null;
        }

        $path = self::pathFromUrl($url);

        if ($path === null) {
            return null;
        }

        $categories = self::query()
            ->where('menu_id', $menu->id)
            ->whereNull('parent_sub_menu_id')
            ->active()
            ->ordered()
            ->get();

        foreach ($categories as $category) {
            $base = $category->categoryBasePath();

            if ($base === null) {
                continue;
            }

            if (preg_match('#^'.preg_quote($base, '#').'/[^/]+$#', $path) === 1) {
                return $category;
            }
        }

        return null;
    }

    public static function resolveBlogCategoryFromPostPath(Menu $menu, string $url): ?self
    {
        return self::resolveCategoryFromPostPath($menu, $url);
    }

    public static function resolveCategoryParentSubMenuId(Menu $menu, string $url, ?int $explicitParentId = null): ?int
    {
        if (! self::isBlogMenu($menu)) {
            return $explicitParentId;
        }

        $path = self::pathFromUrl($url);

        if ($path === null || $path === '/blog') {
            return null;
        }

        $categories = self::query()
            ->where('menu_id', $menu->id)
            ->whereNull('parent_sub_menu_id')
            ->ordered()
            ->get();

        foreach ($categories as $category) {
            $base = $category->categoryBasePath();

            if ($base === null) {
                continue;
            }

            if ($path === $base) {
                return null;
            }

            if (preg_match('#^'.preg_quote($base, '#').'/[^/]+$#', $path) === 1) {
                if ($explicitParentId !== null) {
                    $parent = self::query()->find($explicitParentId);
                    if ($parent && (int) $parent->menu_id === (int) $menu->id) {
                        return $explicitParentId;
                    }
                }

                return $category->id;
            }
        }

        return $explicitParentId;
    }

    public static function resolveBlogParentSubMenuId(Menu $menu, string $url, ?int $explicitParentId = null): ?int
    {
        return self::resolveCategoryParentSubMenuId($menu, $url, $explicitParentId);
    }

    public function showsChildItemsInNav(): bool
    {
        return ! $this->isNavDropdownCategory();
    }

    /**
     * BLOG dropdown: only category links (News, Events, …), never individual posts.
     */
    public function showInSiteHeaderNav(): bool
    {
        $menu = $this->relationLoaded('menu') ? $this->menu : $this->menu()->first();

        if ($menu && self::isBlogMenu($menu)) {
            return $this->isNavDropdownCategory();
        }

        return true;
    }

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

        if ($currentPath === $mine) {
            return true;
        }

        if ($mine !== null && preg_match('#^/where-we-are/([^/]+)$#', $mine, $m)) {
            return request()->routeIs('where-we-are.location')
                && request()->route('slug') === $m[1];
        }

        return false;
    }

    public function isActiveBranch(): bool
    {
        if ($this->isCurrent()) {
            return true;
        }

        foreach ($this->relationLoaded('children') ? $this->children : $this->children()->get() as $child) {
            if ($child->isCurrent() || $child->isActiveBranch()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Breadcrumb trail for page hero (Home → menu → parent submenus → current).
     *
     * @return list<array{label: string, url?: string}>
     */
    public function heroBreadcrumbs(): array
    {
        $items = [
            ['label' => 'Home', 'url' => route('home')],
        ];

        $menu = $this->relationLoaded('menu') ? $this->menu : $this->menu()->first();

        if ($menu && self::isBlogMenu($menu)) {
            return $this->blogHeroBreadcrumbs($menu, $items);
        }

        if ($menu) {
            $this->appendMenuBreadcrumb($items, $menu);
        }

        foreach ($this->ancestorSubMenus() as $ancestor) {
            $this->appendSubMenuBreadcrumb($items, $ancestor);
        }

        $items[] = ['label' => $this->label];

        return $items;
    }

    /**
     * @return list<array{label: string, url?: string}>
     */
    public static function heroBreadcrumbsForPath(string $path): ?array
    {
        $normalized = self::pathFromUrl($path);

        if ($normalized === null) {
            return null;
        }

        $pathAlt = ltrim($normalized, '/');

        $sub = self::query()
            ->where(function ($q) use ($normalized, $pathAlt): void {
                $q->where('url', $normalized)->orWhere('url', $pathAlt);
            })
            ->active()
            ->with(['menu', 'parent'])
            ->first();

        return $sub?->heroBreadcrumbs();
    }

    /**
     * @param  list<array{label: string, url?: string}>  $items
     */
    private function blogHeroBreadcrumbs(Menu $menu, array $items): array
    {
        $items[] = ['label' => $menu->label, 'url' => $menu->siteNavHref()];

        if ($this->isNavDropdownCategory()) {
            $items[] = ['label' => $this->label];

            return $items;
        }

        $category = $this->blogCategoryForPost();
        if ($category && $category->normalizedPath() !== $this->normalizedPath()) {
            $items[] = ['label' => $category->label, 'url' => $category->siteNavHref()];
        }

        $items[] = ['label' => $this->label];

        return $items;
    }

    /**
     * @param  list<array{label: string, url?: string}>  $items
     */
    private function appendMenuBreadcrumb(array &$items, Menu $menu): void
    {
        $menuPath = $menu->normalizedPath();
        $currentPath = $this->normalizedPath();

        if ($currentPath === null) {
            return;
        }

        if ($menuPath !== null && $menuPath === $currentPath && $menuPath !== '/') {
            return;
        }

        $item = ['label' => $menu->label];

        if ($menuPath !== null && $menuPath !== '/' && ! $menu->isDropdownOnlyParentNav()) {
            $item['url'] = $menu->siteNavHref();
        }

        $items[] = $item;
    }

    /**
     * @param  list<array{label: string, url?: string}>  $items
     */
    private function appendSubMenuBreadcrumb(array &$items, self $subMenu): void
    {
        $item = ['label' => $subMenu->label];

        if (! $subMenu->isDropdownOnlyParentNav()) {
            $item['url'] = $subMenu->siteNavHref();
        }

        $items[] = $item;
    }

    /**
     * @return list<self>
     */
    private function ancestorSubMenus(): array
    {
        $ancestors = [];
        $parent = $this->relationLoaded('parent') ? $this->parent : null;

        if ($parent === null && $this->parent_sub_menu_id) {
            $parent = $this->parent()->first();
        }

        while ($parent) {
            array_unshift($ancestors, $parent);
            $parent = $parent->relationLoaded('parent') ? $parent->parent : $parent->parent()->first();
        }

        return $ancestors;
    }

    public function adminSidebarHref(): string
    {
        $path = $this->normalizedPath();
        if ($path !== null) {
            $servicePage = ServicePage::findByPath($path);
            if ($servicePage) {
                return route('admin.service-pages.edit', $servicePage);
            }
        }

        return match ($path) {
            '/about-us' => route('admin.about-page.edit'),
            '/our-story' => route('admin.our-story-page.edit'),
            '/message-from-ceo' => route('admin.ceo-message-page.edit'),
            '/our-team-management' => route('admin.our-team-page.edit'),
            '/career' => route('admin.career-page.edit'),
            '/contact' => route('admin.contact-messages.index'),
            '/quality-certificates-memberships' => route('admin.quality-certificates.index'),
            '/award/honorable-client' => route('admin.honorable-clients.index'),
            default => $this->adminSidebarHrefForPath($path),
        };
    }

    private function adminSidebarHrefForPath(?string $path): string
    {
        if ($path !== null && preg_match('#^/where-we-are/([^/]+)$#', $path, $m)) {
            $location = WhereWeAreLocation::query()->where('slug', $m[1])->first();
            if ($location) {
                return route('admin.where-we-are-locations.edit', $location);
            }
        }

        if ($this->isNavDropdownCategory()) {
            return route('admin.sub-menus.manage', $this);
        }

        if ($this->isBlogCategoryPost()) {
            return route('admin.sub-menus.edit', $this);
        }

        return route('admin.sub-menus.page-sections.index', $this);
    }

    public function adminSidebarIsActive(): bool
    {
        $path = $this->normalizedPath();

        if ($path !== null) {
            $servicePage = ServicePage::findByPath($path);
            if ($servicePage) {
                return request()->routeIs('admin.service-pages.edit')
                    && request()->route('service_page') instanceof ServicePage
                    && (int) request()->route('service_page')->id === (int) $servicePage->id;
            }
        }

        if ($path === '/about-us') {
            return request()->routeIs('admin.about-page.*');
        }
        if ($path === '/our-story') {
            return request()->routeIs('admin.our-story-page.*');
        }
        if ($path === '/message-from-ceo') {
            return request()->routeIs('admin.ceo-message-page.*');
        }
        if ($path === '/our-team-management') {
            return request()->routeIs('admin.our-team-page.*');
        }
        if ($path === '/career') {
            return request()->routeIs('admin.career-page.*');
        }
        if ($path === '/contact') {
            return request()->routeIs('admin.contact-messages.*');
        }
        if ($path === '/quality-certificates-memberships') {
            return request()->routeIs('admin.quality-certificates.*');
        }
        if ($path === '/award/honorable-client') {
            return request()->routeIs('admin.honorable-clients.*');
        }

        if ($path !== null && preg_match('#^/where-we-are/([^/]+)$#', $path, $m)) {
            $routeLocation = request()->route('where_we_are_location');

            return request()->routeIs('admin.where-we-are-locations.*')
                && $routeLocation instanceof WhereWeAreLocation
                && $routeLocation->slug === $m[1];
        }

        $routeSub = request()->route('sub_menu');

        return (request()->routeIs('admin.sub-menus.edit')
            || request()->routeIs('admin.sub-menus.manage')
            || request()->routeIs('admin.sub-menus.page-sections.*')
            || request()->routeIs('admin.who-we-are-sub-menus.*')
            || request()->routeIs('admin.ship-supply-sub-menus.*')
            || request()->routeIs('admin.our-services-sub-menus.*')
            || request()->routeIs('admin.award-sub-menus.*'))
            && $routeSub instanceof self
            && (int) $routeSub->id === (int) $this->id;
    }
}
