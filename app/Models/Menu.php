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

    public function subMenus(): HasMany
    {
        return $this->hasMany(SubMenu::class, 'menu_id');
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
}
