<?php

namespace App\Models;

use App\Support\PublicUploadUrl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HonorableClientPage extends Model
{
    /** Same default port / ship hero used across service & submenu pages. */
    private const DEFAULT_HERO = 'https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=2070&auto=format&fit=crop';

    protected $fillable = [
        'hero_title',
        'hero_background',
        'page_intro',
        'meta_description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public static function singleton(): self
    {
        return self::query()->firstOrCreate([], [
            'hero_title' => 'Honorable Clients',
            'page_intro' => 'We are proud to serve leading maritime companies worldwide.',
            'is_active' => true,
        ]);
    }

    public function heroBackgroundUrl(): string
    {
        return PublicUploadUrl::fromPath($this->hero_background);
    }

    public static function defaultHeroBackgroundUrl(): string
    {
        if (is_file(public_path('menu-page-cover.jpg'))) {
            return asset('menu-page-cover.jpg');
        }

        return self::DEFAULT_HERO;
    }

    public function resolvedHeroBackgroundUrl(): string
    {
        $uploaded = $this->heroBackgroundUrl();

        return $uploaded !== '' ? $uploaded : self::defaultHeroBackgroundUrl();
    }

    public function usesDefaultHeroBackground(): bool
    {
        return $this->heroBackgroundUrl() === '';
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

        return str_starts_with($p, 'honorable-clients/');
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
