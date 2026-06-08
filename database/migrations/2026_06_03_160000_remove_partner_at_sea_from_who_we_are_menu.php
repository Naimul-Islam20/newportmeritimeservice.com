<?php

use App\Models\Menu;
use App\Models\SubMenu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * @var list<array{label: string, url: string, sort_order: int}>
     */
    private array $pages = [
        ['label' => 'About Us', 'url' => '/about-us', 'sort_order' => 0],
        ['label' => 'Where we are', 'url' => '/where-we-are', 'sort_order' => 1],
        ['label' => 'Our Values - Mission & Vision', 'url' => '/our-values-mission-vision', 'sort_order' => 2],
        ['label' => 'Our commitment', 'url' => '/our-commitment', 'sort_order' => 3],
        ['label' => 'Why Choose NewPort?', 'url' => '/why-choose-newport', 'sort_order' => 4],
    ];

    public function up(): void
    {
        $parent = $this->resolveWhoWeAreMenu();

        if (! $parent) {
            return;
        }

        SubMenu::query()
            ->where('menu_id', $parent->id)
            ->whereNull('parent_sub_menu_id')
            ->where(function ($q): void {
                $q->where('url', '/')
                    ->orWhere('url', '')
                    ->orWhereRaw('LOWER(label) LIKE ?', ['%your partner at sea%']);
            })
            ->update(['is_active' => false]);

        foreach ($this->pages as $page) {
            SubMenu::query()
                ->where('menu_id', $parent->id)
                ->whereNull('parent_sub_menu_id')
                ->where(function ($q) use ($page): void {
                    $q->where('url', $page['url'])
                        ->orWhere('url', ltrim($page['url'], '/'));
                })
                ->update([
                    'label' => $page['label'],
                    'sort_order' => $page['sort_order'],
                    'is_active' => true,
                ]);
        }
    }

    public function down(): void
    {
        $parent = $this->resolveWhoWeAreMenu();

        if (! $parent) {
            return;
        }

        SubMenu::query()->updateOrCreate(
            [
                'menu_id' => $parent->id,
                'url' => '/',
                'parent_sub_menu_id' => null,
            ],
            [
                'label' => 'Who We Are/Home: Your Partner at Sea',
                'sort_order' => 0,
                'is_active' => true,
            ],
        );

        $withHome = [
            ['label' => 'Who We Are/Home: Your Partner at Sea', 'url' => '/', 'sort_order' => 0],
            ...$this->pages,
        ];

        foreach ($withHome as $page) {
            if ($page['url'] === '/') {
                continue;
            }

            SubMenu::query()
                ->where('menu_id', $parent->id)
                ->whereNull('parent_sub_menu_id')
                ->where(function ($q) use ($page): void {
                    $q->where('url', $page['url'])
                        ->orWhere('url', ltrim($page['url'], '/'));
                })
                ->update(['sort_order' => $page['sort_order'] + 1]);
        }
    }

    private function resolveWhoWeAreMenu(): ?Menu
    {
        return Menu::query()
            ->whereRaw('LOWER(label) LIKE ?', ['%who we are%'])
            ->orderBy('sort_order')
            ->first()
            ?? Menu::query()->where('url', '/')->orderBy('sort_order')->first();
    }
};
