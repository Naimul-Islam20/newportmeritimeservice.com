<?php

use App\Models\Menu;
use App\Models\SubMenu;
use App\Models\WhereWeAreLocation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * @var list<array{label: string, slug: string, sort_order: int, hero_title: string, office_title: string, paragraphs: list<string>}>
     */
    private array $locations = [
        [
            'label' => 'Istanbul',
            'slug' => 'istanbul',
            'sort_order' => 0,
            'hero_title' => 'Istanbul',
            'office_title' => 'Head Office & Warehouse',
            'paragraphs' => [
                'We have been serving the world shipping industry from our Istanbul headquarters for decades. The Bosphorus and Dardanelles are critical maritime passages, making Istanbul a strategic hub for transit and port supply.',
                'Transit delivery to vessels during passage through the Bosphorus constitutes a significant share of our business. Please visit our locations overview for more regional detail.',
            ],
        ],
        [
            'label' => 'Rotterdam',
            'slug' => 'rotterdam',
            'sort_order' => 1,
            'hero_title' => 'Rotterdam',
            'office_title' => 'Rotterdam Office',
            'paragraphs' => [
                'Our presence in the ARA region supports owners and operators with reliable ship supply and logistics across major North Sea ports.',
            ],
        ],
        [
            'label' => 'Hamburg',
            'slug' => 'hamburg',
            'sort_order' => 2,
            'hero_title' => 'Hamburg',
            'office_title' => 'Hamburg Office',
            'paragraphs' => [
                'Serving the Hamburg region and surrounding ports with technical stores, provisions, and coordinated port delivery.',
            ],
        ],
        [
            'label' => 'Athens',
            'slug' => 'athens',
            'sort_order' => 3,
            'hero_title' => 'Athens',
            'office_title' => 'Athens Marketing Office',
            'paragraphs' => [
                'Our Athens office supports marketing and customer relations across the Eastern Mediterranean.',
            ],
        ],
        [
            'label' => 'Mersin',
            'slug' => 'mersin',
            'sort_order' => 4,
            'hero_title' => 'Mersin',
            'office_title' => 'Mersin Branch Office & Warehouse',
            'paragraphs' => [
                'Mersin branch operations extend our Turkey coverage with dedicated warehouse capacity and port delivery services.',
            ],
        ],
        [
            'label' => 'Tuzla',
            'slug' => 'tuzla',
            'sort_order' => 5,
            'hero_title' => 'Tuzla',
            'office_title' => 'Tuzla Branch Office',
            'paragraphs' => [
                'Located in the Tuzla shipyard and industrial zone, our branch supports repair, new-building, and alongside supply.',
            ],
        ],
    ];

    public function up(): void
    {
        $whoWeAre = Menu::query()
            ->whereRaw('LOWER(label) LIKE ?', ['%who we are%'])
            ->first();

        if (! $whoWeAre) {
            return;
        }

        $whereWeAre = SubMenu::query()
            ->where('menu_id', $whoWeAre->id)
            ->where(function ($q): void {
                $q->where('url', '/where-we-are')
                    ->orWhere('url', 'where-we-are')
                    ->orWhereRaw('LOWER(label) LIKE ?', ['%where we are%']);
            })
            ->first();

        if (! $whereWeAre) {
            $whereWeAre = SubMenu::query()->create([
                'menu_id' => $whoWeAre->id,
                'label' => 'Where We Are',
                'url' => '/where-we-are',
                'sort_order' => 1,
                'is_active' => true,
            ]);
        }

        $siteName = \App\Models\SiteDetail::resolvedSiteName();

        foreach ($this->locations as $loc) {
            $url = '/where-we-are/'.$loc['slug'];

            $child = SubMenu::query()->updateOrCreate(
                [
                    'menu_id' => $whoWeAre->id,
                    'parent_sub_menu_id' => $whereWeAre->id,
                    'url' => $url,
                ],
                [
                    'label' => $loc['label'],
                    'sort_order' => $loc['sort_order'],
                    'is_active' => true,
                ],
            );

            WhereWeAreLocation::query()->updateOrCreate(
                ['slug' => $loc['slug']],
                [
                    'sub_menu_id' => $child->id,
                    'hero_title' => $loc['hero_title'],
                    'meta_description' => $loc['hero_title'].' — '.$siteName.' office and services.',
                    'eyebrow' => 'Where We Are',
                    'office_title' => $loc['office_title'],
                    'body_paragraphs' => $loc['paragraphs'],
                    'brochure_label' => 'Download Brochure PDF',
                    'brochure_url' => '#',
                    'show_quality_block' => true,
                    'quality_block_title' => 'Quality Certificates & Memberships',
                    'quality_block_lead' => 'Click on the image to view the certificates.',
                    'contact_cta_label' => 'Contact Us',
                    'contact_cta_url' => '/contact',
                    'sort_order' => $loc['sort_order'],
                    'is_active' => true,
                ],
            );
        }
    }

    public function down(): void
    {
        $slugs = collect($this->locations)->pluck('slug')->all();
        WhereWeAreLocation::query()->whereIn('slug', $slugs)->delete();

        SubMenu::query()
            ->whereNotNull('parent_sub_menu_id')
            ->where(function ($q) use ($slugs): void {
                foreach ($slugs as $slug) {
                    $q->orWhere('url', '/where-we-are/'.$slug);
                }
            })
            ->delete();
    }
};
