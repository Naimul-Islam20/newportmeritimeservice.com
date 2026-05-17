<?php

namespace App\Support;

class SectionStrip
{
    /**
     * Alternating section backgrounds via CSS vars --section-strip-a / --section-strip-b (Site Details).
     *
     * @return array<string, mixed>
     */
    public static function viewData(?string $sectionStrip = 'primary'): array
    {
        $strip = $sectionStrip ?? 'primary';
        $onPrimaryStrip = $strip === 'primary';

        return [
            'sectionStrip' => $strip,
            'onPrimaryStrip' => $onPrimaryStrip,
            'stripSectionClass' => $onPrimaryStrip ? 'section-strip-bg-primary' : 'section-strip-bg-secondary',
            'stripMiniClass' => 'text-primary',
            'stripTitleClass' => 'text-secondary',
            'stripBodyClass' => 'text-foreground/70',
            'stripCardClass' => 'bg-background text-foreground',
            'stripCardBorderClass' => $onPrimaryStrip ? 'border-primary/30' : 'border-secondary/30',
        ];
    }
}
