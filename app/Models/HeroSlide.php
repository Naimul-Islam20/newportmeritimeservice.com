<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HeroSlide extends Model
{
    protected $fillable = [
        'title',
        'description',
        'button_label',
        'button_url',
        'image_path',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function imagePublicUrl(): string
    {
        if ($this->image_path === null || $this->image_path === '') {
            return '';
        }

        $path = str_replace('\\', '/', ltrim($this->image_path, '/'));

        if (Storage::disk('public_site')->exists($path)) {
            return asset($path);
        }

        if (Storage::disk('public')->exists($path)) {
            return asset('storage/'.$path);
        }

        return '';
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

        return str_starts_with($raw, '/') ? $raw : '/'.$raw;
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
