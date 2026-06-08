<?php

namespace App\Models;

use App\Support\PublicUploadUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HonorableClient extends Model
{
    protected $fillable = [
        'name',
        'logo_path',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
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

    public function logoPublicUrl(): string
    {
        return PublicUploadUrl::fromPath($this->logo_path);
    }

    public function hasLogo(): bool
    {
        return $this->logoPublicUrl() !== '';
    }

    /**
     * @return Collection<int, static>
     */
    public static function forPublicPage(): Collection
    {
        return self::query()->active()->ordered()->get();
    }

    public static function isManagedUploadPath(?string $path): bool
    {
        if (! is_string($path) || $path === '') {
            return false;
        }
        if (preg_match('#^https?://#i', $path)) {
            return false;
        }
        $p = ltrim(str_replace('\\', '/', $path), '/');

        return str_starts_with($p, 'honorable-clients/logos/');
    }

    public static function deleteManagedUpload(?string $path): void
    {
        if (! self::isManagedUploadPath($path)) {
            return;
        }
        $p = ltrim(str_replace('\\', '/', $path), '/');
        if (Storage::disk('public_site')->exists($p)) {
            Storage::disk('public_site')->delete($p);
        }
    }
}
