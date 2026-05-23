<?php

namespace App\Models;

use App\Support\PublicUploadUrl;
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
        'data',
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
            'data' => 'array',
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

    public function imagePublicUrl(): string
    {
        return PublicUploadUrl::fromPath($this->image_path);
    }

    /**
     * @return array{type: 'none'|'youtube', embed_url: string}
     */
    public function videoModalPayload(): array
    {
        $url = data_get($this->data, 'video_url');

        return AboutPage::videoModalPayload(is_string($url) ? $url : null);
    }

    public function resolvedButtonHref(): string
    {
        return $this->resolvedButtonHrefFor($this->button_url);
    }

    /**
     * @return list<array{path?: string|null, title?: string|null, url?: string|null}>
     */
    public function logoCarouselItems(): array
    {
        $raw = data_get($this->data, 'items');
        if (! is_array($raw)) {
            return [];
        }

        $out = [];
        foreach ($raw as $item) {
            if (! is_array($item)) {
                continue;
            }
            $path = data_get($item, 'path');
            $title = data_get($item, 'title');
            $hasPath = is_string($path) && trim($path) !== '';
            $hasTitle = is_string($title) && trim($title) !== '';
            if (! $hasPath && ! $hasTitle) {
                continue;
            }
            $out[] = [
                'path' => $hasPath ? trim($path) : null,
                'title' => $hasTitle ? trim($title) : null,
                'url' => $this->resolveOptionalStoredString(data_get($item, 'url')),
            ];
        }

        return $out;
    }

    private function resolveOptionalStoredString(mixed $value): ?string
    {
        $value = is_string($value) ? trim($value) : '';

        return $value !== '' ? $value : null;
    }

    public function resolvedButtonHrefFor(mixed $url): string
    {
        $raw = trim((string) ($url ?? ''));

        if ($raw === '' || $raw === '#') {
            return '#';
        }

        if (preg_match('#^https?://#i', $raw)) {
            return $raw;
        }

        if (str_contains($raw, '@') && ! str_contains($raw, '/')) {
            return 'mailto:'.$raw;
        }

        $path = Str::startsWith($raw, '/') ? $raw : '/'.$raw;

        return '/'.ltrim($path, '/');
    }
}
