<?php

use App\Support\PublicUploadUrl;
use App\Support\SectionStrip;

if (! function_exists('public_upload_url')) {
    /** Public URL for a file under /public or /storage, or "" if missing. */
    function public_upload_url(?string $path): string
    {
        return PublicUploadUrl::fromPath($path);
    }
}

if (! function_exists('public_upload_url_or')) {
    function public_upload_url_or(?string $path, ?string $fallback = null): string
    {
        return PublicUploadUrl::fromPathOr($path, $fallback);
    }
}

if (! function_exists('section_strip_view_data')) {
    /**
     * @return array<string, mixed>
     */
    function section_strip_view_data(?string $sectionStrip = 'primary'): array
    {
        return SectionStrip::viewData($sectionStrip);
    }
}
