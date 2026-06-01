<?php

namespace App\Models;

use App\Support\PublicUploadUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SubMenu extends Model
{
    protected $table = 'sub_menus';

    protected $fillable = [
        'menu_id',
        'label',
        'url',
        'description',
        'page_content',
        'cover_image_path',
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

    /**
     * Same background URL as the submenu page hero (cover upload, else default cover asset).
     */
    public function pageHeroBackgroundUrl(): string
    {
        $cover = $this->coverImageUrl();

        if ($cover !== '') {
            return $cover;
        }

        return PublicUploadUrl::fromPathOr('menu-page-cover.jpg', '/menu-page-cover.jpg');
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
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
            default => route('admin.sub-menus.page-sections.index', $this),
        };
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

        $routeSub = request()->route('sub_menu');

        return (request()->routeIs('admin.sub-menus.edit') || request()->routeIs('admin.sub-menus.page-sections.*'))
            && $routeSub instanceof self
            && (int) $routeSub->id === (int) $this->id;
    }
}
