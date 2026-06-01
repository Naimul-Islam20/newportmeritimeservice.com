<?php

use App\Models\CertificateGroup;
use App\Models\CertificatePage;
use App\Models\HomeSection;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        CertificatePage::singleton();

        if (CertificateGroup::query()->exists()) {
            $this->linkHomeCarousel();

            return;
        }

        $groups = [
            [
                'title' => 'OUR TURKEY QUALITY CERTIFICATES',
                'slug' => 'turkey-quality-certificates',
                'intro' => 'Click on the image to view the certificates.',
                'layout' => CertificateGroup::LAYOUT_GRID,
                'sort_order' => 1,
                'show_divider_before' => false,
            ],
            [
                'title' => 'OUR MEMBERSHIP IN TURKEY',
                'slug' => 'membership-turkey',
                'intro' => null,
                'layout' => CertificateGroup::LAYOUT_GRID,
                'sort_order' => 2,
                'show_divider_before' => false,
            ],
            [
                'title' => 'RELIABLE SUPPLIER CERTIFICATE',
                'slug' => 'reliable-supplier-certificate',
                'intro' => null,
                'layout' => CertificateGroup::LAYOUT_STACK,
                'sort_order' => 3,
                'show_divider_before' => false,
            ],
            [
                'title' => 'TURKISH MARINE ENVIRONMENT PROTECTION CERTIFICATE',
                'slug' => 'turkish-marine-environment-protection',
                'intro' => null,
                'layout' => CertificateGroup::LAYOUT_STACK,
                'sort_order' => 4,
                'show_divider_before' => false,
            ],
            [
                'title' => 'OUR ROTTERDAM QUALITY CERTIFICATES',
                'slug' => 'rotterdam-quality-certificates',
                'intro' => 'Click on the image to view the certificates.',
                'layout' => CertificateGroup::LAYOUT_GRID,
                'sort_order' => 5,
                'show_divider_before' => true,
            ],
            [
                'title' => 'OUR MEMBERSHIP IN ROTTERDAM',
                'slug' => 'membership-rotterdam',
                'intro' => null,
                'layout' => CertificateGroup::LAYOUT_GRID,
                'sort_order' => 6,
                'show_divider_before' => false,
            ],
        ];

        foreach ($groups as $group) {
            CertificateGroup::create(array_merge($group, ['is_active' => true]));
        }

        $this->linkHomeCarousel();
    }

    private function linkHomeCarousel(): void
    {
        HomeSection::query()
            ->where('block_type', 'logo_carousel')
            ->where('variant', 'certificates')
            ->each(function (HomeSection $section): void {
                $section->update([
                    'button_url' => '/quality-certificates-memberships',
                ]);
            });
    }

    public function down(): void
    {
        CertificateGroup::query()->each(fn (CertificateGroup $g) => $g->delete());
        CertificatePage::query()->delete();
    }
};
