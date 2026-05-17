<?php

use App\Support\SectionStrip;

if (! function_exists('section_strip_view_data')) {
    /**
     * @return array<string, mixed>
     */
    function section_strip_view_data(?string $sectionStrip = 'primary'): array
    {
        return SectionStrip::viewData($sectionStrip);
    }
}
