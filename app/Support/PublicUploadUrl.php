<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

final class PublicUploadUrl
{
    /**
     * Public URL for an uploaded file (relative path), or empty string if missing.
     */
    public static function fromPath(?string $value): string
    {
        if (! is_string($value) || trim($value) === '') {
            return '';
        }

        $value = trim($value);
        if (preg_match('#^https?://#i', $value)) {
            return $value;
        }

        $path = str_replace('\\', '/', ltrim($value, '/'));

        if (Storage::disk('public_site')->exists($path)) {
            return '/'.$path;
        }

        if (Storage::disk('public')->exists($path)) {
            return '/storage/'.$path;
        }

        return '';
    }

    /**
     * Like fromPath(), but falls back when the file is missing.
     */
    public static function fromPathOr(?string $value, ?string $fallback = null): string
    {
        $url = self::fromPath($value);

        return $url !== '' ? $url : (string) ($fallback ?? '');
    }
}
