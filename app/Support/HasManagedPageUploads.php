<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

trait HasManagedPageUploads
{
    public static function imageSrc(?string $value): string
    {
        return PublicUploadUrl::fromPath($value);
    }

    public static function isManagedUploadPath(?string $path, string $prefix): bool
    {
        if (! is_string($path) || $path === '') {
            return false;
        }
        if (preg_match('#^https?://#i', $path)) {
            return false;
        }
        $p = ltrim(str_replace('\\', '/', $path), '/');

        return str_starts_with($p, rtrim($prefix, '/').'/');
    }

    public static function deleteManagedUpload(?string $path): void
    {
        if (! is_string($path) || $path === '') {
            return;
        }
        if (preg_match('#^https?://#i', $path)) {
            return;
        }
        $p = ltrim(str_replace('\\', '/', $path), '/');
        if (Storage::disk('public_site')->exists($p)) {
            Storage::disk('public_site')->delete($p);
        }
        if (Storage::disk('public')->exists($p)) {
            Storage::disk('public')->delete($p);
        }
    }
}
