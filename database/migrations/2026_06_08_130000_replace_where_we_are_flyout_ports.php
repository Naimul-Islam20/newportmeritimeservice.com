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
     * @var list<array{label: string, slug: string, sort_order: int}>
     */
    private array $ports = [
        ['label' => 'Chattogram port', 'slug' => 'chattogram-port', 'sort_order' => 0],
        ['label' => 'Mongla port', 'slug' => 'mongla-port', 'sort_order' => 1],
        ['label' => 'Payra port', 'slug' => 'payra-port', 'sort_order' => 2],
        ['label' => "Cox's Bazar port", 'slug' => 'coxs-bazar-port', 'sort_order' => 3],
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
            ->whereNull('parent_sub_menu_id')
            ->where(function ($q): void {
                $q->where('url', '/where-we-are')
                    ->orWhere('url', 'where-we-are')
                    ->orWhereRaw('LOWER(label) LIKE ?', ['%where we are%']);
            })
            ->first();

        if (! $whereWeAre) {
            return;
        }

        foreach ($this->retiredSlugs as $slug) {
            SubMenu::query()
                ->where('menu_id', $whoWeAre->id)
                ->where('parent_sub_menu_id', $whereWeAre->id)
                ->where(function ($q) use ($slug): void {
                    $q->where('url', '/where-we-are/'.$slug)
                        ->orWhere('url', 'where-we-are/'.$slug);
                })
                ->update(['is_active' => false]);

            WhereWeAreLocation::query()
                ->where('slug', $slug)
                ->update(['is_active' => false]);
        }

        foreach ($this->ports as $port) {
            SubMenu::query()->updateOrCreate(
                [
                    'menu_id' => $whoWeAre->id,
                    'parent_sub_menu_id' => $whereWeAre->id,
                    'url' => '/where-we-are/'.$port['slug'],
                ],
                [
                    'label' => $port['label'],
                    'sort_order' => $port['sort_order'],
                    'is_active' => true,
                ],
            );
        }
    }

    public function down(): void
    {
        $whoWeAre = Menu::query()
            ->whereRaw('LOWER(label) LIKE ?', ['%who we are%'])
            ->first();

        if (! $whoWeAre) {
            return;
        }

        $whereWeAre = SubMenu::query()
            ->where('menu_id', $whoWeAre->id)
            ->whereNull('parent_sub_menu_id')
            ->where(function ($q): void {
                $q->where('url', '/where-we-are')
                    ->orWhere('url', 'where-we-are')
                    ->orWhereRaw('LOWER(label) LIKE ?', ['%where we are%']);
            })
            ->first();

        if (! $whereWeAre) {
            return;
        }

        foreach ($this->ports as $port) {
            SubMenu::query()
                ->where('menu_id', $whoWeAre->id)
                ->where('parent_sub_menu_id', $whereWeAre->id)
                ->where(function ($q) use ($port): void {
                    $q->where('url', '/where-we-are/'.$port['slug'])
                        ->orWhere('url', 'where-we-are/'.$port['slug']);
                })
                ->delete();
        }

        foreach ($this->retiredSlugs as $slug) {
            SubMenu::query()
                ->where('menu_id', $whoWeAre->id)
                ->where('parent_sub_menu_id', $whereWeAre->id)
                ->where(function ($q) use ($slug): void {
                    $q->where('url', '/where-we-are/'.$slug)
                        ->orWhere('url', 'where-we-are/'.$slug);
                })
                ->update(['is_active' => true]);

            WhereWeAreLocation::query()
                ->where('slug', $slug)
                ->update(['is_active' => true]);
        }
    }
};
