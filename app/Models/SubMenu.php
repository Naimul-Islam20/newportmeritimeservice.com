<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubMenu extends Model
{
    protected $table = 'sub_menus';

    protected $fillable = [
        'menu_id',
        'label',
        'url',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
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
}
