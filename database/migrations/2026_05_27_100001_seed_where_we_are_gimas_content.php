<?php

use App\Models\WhereWeAreLocation;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $updates = [
            'istanbul' => [
                'region_label' => 'TURKEY',
                'sidebar_label' => 'Istanbul Head Office & Warehouse',
                'office_title' => 'Head Office & Warehouse',
                'brochure_lead' => 'Download company brochure for Turkey from the link below',
                'certificate_group_slug' => 'turkey-quality-certificates',
                'membership_group_slug' => 'membership-turkey',
                'quality_block_lead' => 'Click on the image to view the certificates.',
                'body_link_label' => 'All Ports of Turkey',
                'body_link_url' => '/locations/all-ports-of-turkey',
                'body_paragraphs' => [
                    'We have been serving the world shipping industry from our Istanbul headquarters for decades. The Bosphorus and Dardanelles are critical maritime passages, making Istanbul a strategic hub for transit and port supply.',
                    'Transit delivery to vessels during passage through the Bosphorus constitutes a significant share of our business. Please visit',
                ],
                'sidebar_extras' => [
                    ['label' => 'Quality Certificates & Memberships', 'url' => '#where-location-quality'],
                ],
            ],
            'tuzla' => [
                'region_label' => 'TURKEY',
                'sidebar_label' => 'Tuzla Branch Office',
            ],
            'mersin' => [
                'region_label' => 'TURKEY',
                'sidebar_label' => 'Mersin Branch Office & Warehouse',
            ],
            'rotterdam' => [
                'region_label' => 'ROTTERDAM',
                'sidebar_label' => 'Rotterdam Office',
                'brochure_lead' => 'Download company brochure for Rotterdam from the link below',
                'certificate_group_slug' => null,
                'membership_group_slug' => 'membership-rotterdam',
                'sidebar_extras' => [
                    ['label' => 'Ports in the ARA area', 'url' => '/locations/ports-in-ara'],
                    ['label' => 'Quality Certificates & Memberships', 'url' => '#where-location-quality'],
                ],
            ],
            'hamburg' => [
                'region_label' => 'HAMBURG',
                'sidebar_label' => 'Hamburg Office',
                'sidebar_extras' => [
                    ['label' => 'Ports in the Hamburg region', 'url' => '/locations'],
                ],
            ],
            'athens' => [
                'region_label' => 'ATHENS',
                'sidebar_label' => 'Athens Marketing Office',
            ],
        ];

        foreach ($updates as $slug => $data) {
            WhereWeAreLocation::query()->where('slug', $slug)->update($data);
        }
    }

    public function down(): void
    {
        WhereWeAreLocation::query()->update([
            'region_label' => null,
            'sidebar_label' => null,
            'sidebar_extras' => null,
            'brochure_lead' => null,
            'certificate_group_slug' => null,
            'membership_group_slug' => null,
            'body_link_label' => null,
            'body_link_url' => null,
        ]);
    }
};
