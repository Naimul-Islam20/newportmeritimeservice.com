<?php

use App\Models\Menu;
use App\Models\SubMenu;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * @var list<array{label: string, url: string, sort_order: int}>
     */
    private array $pages = [
        ['label' => 'About Us', 'url' => '/about-us', 'sort_order' => 0],
        ['label' => 'Where We Are', 'url' => '/where-we-are', 'sort_order' => 1],
        ['label' => 'Our Story', 'url' => '/our-story', 'sort_order' => 2],
        ['label' => 'Message from the CEO', 'url' => '/message-from-ceo', 'sort_order' => 3],
        ['label' => 'Our Team', 'url' => '/our-team-management', 'sort_order' => 4],
        ['label' => 'Contact Us', 'url' => '/contact', 'sort_order' => 5],
    ];

    public function up(): void
    {
        $parent = $this->resolveWhoWeAreMenu();

        if (! $parent) {
            return;
        }

        if (Str::lower(trim($parent->label)) === 'home') {
            $parent->update(['label' => 'WHO WE ARE']);
        }

        foreach ($this->pages as $page) {
            $exists = SubMenu::query()
                ->where('menu_id', $parent->id)
                ->where(function ($q) use ($page): void {
                    $q->where('url', $page['url'])
                        ->orWhere('url', ltrim($page['url'], '/'));
                })
                ->exists();

            if ($exists) {
                SubMenu::query()
                    ->where('menu_id', $parent->id)
                    ->where(function ($q) use ($page): void {
                        $q->where('url', $page['url'])
                            ->orWhere('url', ltrim($page['url'], '/'));
                    })
                    ->update([
                        'label' => $page['label'],
                        'sort_order' => $page['sort_order'],
                        'is_active' => true,
                    ]);

                continue;
            }

            SubMenu::query()->create([
                'menu_id' => $parent->id,
                'label' => $page['label'],
                'url' => $page['url'],
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

        $paths = collect($this->pages)->pluck('url')->flatMap(fn (string $url) => [$url, ltrim($url, '/')]);

        SubMenu::query()
            ->where('menu_id', $parent->id)
            ->whereIn('url', $paths->unique()->all())
            ->whereNotIn('url', ['/about-us', '/where-we-are', 'about-us', 'where-we-are'])
            ->delete();
    }

    private function resolveWhoWeAreMenu(): ?Menu
    {
        $whoWeAre = Menu::query()
            ->whereRaw('LOWER(label) LIKE ?', ['%who we are%'])
            ->orderBy('sort_order')
            ->first();

        if ($whoWeAre) {
            return $whoWeAre;
        }

        $homeWithChildren = Menu::query()
            ->where('url', '/')
            ->whereHas('subMenus')
            ->orderBy('sort_order')
            ->first();

        if ($homeWithChildren) {
            return $homeWithChildren;
        }

        return Menu::query()->where('url', '/')->orderBy('sort_order')->first();
    }
};
