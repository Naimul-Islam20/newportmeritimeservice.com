<?php

use App\Models\Menu;
use App\Models\SubMenu;
use App\Models\WhereWeAreLocation;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * @var list<string>
     */
    private array $retiredSlugs = [
        'istanbul',
        'rotterdam',
        'hamburg',
        'athens',
        'mersin',
        'tuzla',
    ];

    /**
     * @var list<array{slug: string, hero_title: string, office_title: string, hero_background: string, sort_order: int}>
     */
    private array $ports = [
        [
            'slug' => 'chattogram-port',
            'hero_title' => 'Chattogram Port',
            'office_title' => 'Chattogram Port Authority',
            'hero_background' => 'https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?auto=format&fit=crop&w=900&h=520&q=80',
            'sort_order' => 0,
        ],
        [
            'slug' => 'mongla-port',
            'hero_title' => 'Mongla Port',
            'office_title' => 'Mongla Port Authority',
            'hero_background' => 'https://images.unsplash.com/photo-1578575437136-9c13f6c966fc?auto=format&fit=crop&w=900&h=520&q=80',
            'sort_order' => 1,
        ],
        [
            'slug' => 'payra-port',
            'hero_title' => 'Payra Port',
            'office_title' => 'Payra Port Authority',
            'hero_background' => 'https://images.unsplash.com/photo-1494412574743-01927c452424?auto=format&fit=crop&w=900&h=520&q=80',
            'sort_order' => 2,
        ],
        [
            'slug' => 'coxs-bazar-port',
            'hero_title' => "Cox's Bazar Port",
            'office_title' => 'Fishing Harbour',
            'hero_background' => 'https://images.unsplash.com/photo-1565008576549-57569a49371d?auto=format&fit=crop&w=900&h=520&q=80',
            'sort_order' => 3,
        ],
    ];

    public function up(): void
    {
        WhereWeAreLocation::query()
            ->whereIn('slug', $this->retiredSlugs)
            ->update(['is_active' => false]);

        $siteName = \App\Models\SiteDetail::resolvedSiteName();
        [$menu, $parent] = $this->resolveWhereWeAreSubMenu();

        foreach ($this->ports as $port) {
            $location = WhereWeAreLocation::query()->updateOrCreate(
                ['slug' => $port['slug']],
                [
                    'hero_title' => $port['hero_title'],
                    'office_title' => $port['office_title'],
                    'hero_background' => $port['hero_background'],
                    'meta_description' => $port['hero_title'].' — '.$siteName,
                    'eyebrow' => 'Where We Are',
                    'contact_cta_label' => 'Contact Us',
                    'contact_cta_url' => '/contact',
                    'show_quality_block' => false,
                    'sort_order' => $port['sort_order'],
                    'is_active' => true,
                ],
            );

            if ($menu && $parent) {
                $location->syncSubMenu($menu, $parent);
            }
        }
    }

    public function down(): void
    {
        foreach ($this->ports as $port) {
            WhereWeAreLocation::query()
                ->where('slug', $port['slug'])
                ->update(['is_active' => false]);
        }
    }

    /**
     * @return array{0: ?Menu, 1: ?SubMenu}
     */
    private function resolveWhereWeAreSubMenu(): array
    {
        $menu = Menu::query()
            ->whereRaw('LOWER(label) LIKE ?', ['%who we are%'])
            ->first();

        if (! $menu) {
            return [null, null];
        }

        $parent = SubMenu::query()
            ->where('menu_id', $menu->id)
            ->whereNull('parent_sub_menu_id')
            ->where(function ($q): void {
                $q->where('url', '/where-we-are')
                    ->orWhere('url', 'where-we-are')
                    ->orWhereRaw('LOWER(label) LIKE ?', ['%where we are%']);
            })
            ->first();

        return [$menu, $parent];
    }
};
