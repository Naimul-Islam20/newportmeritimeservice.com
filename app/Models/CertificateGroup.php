<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CertificateGroup extends Model
{
    public const LAYOUT_GRID = 'grid';

    public const LAYOUT_STACK = 'stack';

    protected $fillable = [
        'title',
        'slug',
        'intro',
        'layout',
        'sort_order',
        'show_divider_before',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'show_divider_before' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (CertificateGroup $group): void {
            if (! filled($group->slug)) {
                $group->slug = self::uniqueSlug($group->title ?? 'section', $group->id);
            }
        });
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        if ($base === '') {
            $base = 'section';
        }
        $slug = $base;
        $n = 2;
        while (
            self::query()
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $base.'-'.$n;
            $n++;
        }

        return $slug;
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(QualityCertificate::class);
    }

    public function activeCertificates(): HasMany
    {
        return $this->certificates()->where('is_active', true)->ordered();
    }

    public function isGridLayout(): bool
    {
        return $this->layout !== self::LAYOUT_STACK;
    }
}
