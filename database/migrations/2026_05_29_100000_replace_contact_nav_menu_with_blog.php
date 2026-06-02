<?php

use App\Models\Menu;
use App\Models\SubMenu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /** @var list<array{label: string, url: string, sort_order: int}> */
    private array $blogSubMenus = [
        ['label' => 'News', 'url' => '/blog/news', 'sort_order' => 0],
        ['label' => 'Events', 'url' => '/blog/events', 'sort_order' => 1],
        ['label' => 'Gallery', 'url' => '/blog/gallery', 'sort_order' => 2],
        ['label' => 'Recipes', 'url' => '/blog/recipes', 'sort_order' => 3],
        ['label' => 'Newport TV', 'url' => '/blog/newport-tv', 'sort_order' => 4],
    ];

    public function up(): void
    {
        Menu::query()
            ->where(function ($q): void {
                $q->where('url', '/contact')
                    ->orWhere('url', 'contact');
            })
            ->whereRaw('LOWER(label) LIKE ?', ['%contact%'])
            ->whereDoesntHave('subMenus')
            ->update(['is_active' => false]);

        $blog = Menu::query()->updateOrCreate(
            [
                'url' => '/blog',
            ],
            [
                'label' => 'BLOG',
                'sort_order' => 40,
                'is_active' => true,
                'show_submenus_on_page' => false,
            ],
        );

        foreach ($this->blogSubMenus as $item) {
            SubMenu::query()->updateOrCreate(
                [
                    'menu_id' => $blog->id,
                    'url' => $item['url'],
                    'parent_sub_menu_id' => null,
                ],
                [
                    'label' => $item['label'],
                    'sort_order' => $item['sort_order'],
                    'is_active' => true,
                ],
            );
        }
    }

    public function down(): void
    {
        $blog = Menu::query()
            ->where(function ($q): void {
                $q->where('url', '/blog')->orWhere('url', 'blog');
            })
            ->first();

        if ($blog) {
            SubMenu::query()
                ->where('menu_id', $blog->id)
                ->whereIn('url', collect($this->blogSubMenus)->pluck('url'))
                ->delete();

            $blog->delete();
        }

        Menu::query()
            ->where(function ($q): void {
                $q->where('url', '/contact')->orWhere('url', 'contact');
            })
            ->whereRaw('LOWER(label) LIKE ?', ['%contact%'])
            ->update(['is_active' => true]);
    }
};
