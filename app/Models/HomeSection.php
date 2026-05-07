<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class HomeSection extends Model
{
    protected $fillable = [
        'block_type',
        'variant',
        'two_column_mode',
        'layout_width',
        'image_path',
        'image_alt',
        'menu_id',
        'mini_title',
        'title',
        'description',
        'points',
        'left_content',
        'right_content',
        'button_label',
        'button_url',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'menu_id' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'points' => 'array',
            'left_content' => 'array',
            'right_content' => 'array',
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
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function resolvedButtonHref(): string
    {
        $raw = trim((string) ($this->button_url ?? ''));

        if ($raw === '' || $raw === '#') {
            return '#';
        }

        if (preg_match('#^https?://#i', $raw)) {
            return $raw;
        }

        $path = Str::startsWith($raw, '/') ? $raw : '/'.$raw;

        return '/'.ltrim($path, '/');
    }
}
