<?php

namespace App\Models;

use App\Support\PublicUploadUrl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CertificatePage extends Model
{
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
            'hero_title' => 'Quality Certificates & Memberships',
            'page_intro' => 'Quality Certificates & Memberships',
            'is_active' => true,
        ]);
    }

    public function heroBackgroundUrl(): string
    {
        return PublicUploadUrl::fromPath($this->hero_background);
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

        return str_starts_with($p, 'certificate-pages/');
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
