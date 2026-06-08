<?php

use App\Models\Menu;
use App\Models\SubMenu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * @var list<array{label: string, url: string, sort_order: int}>
     */
    private array $shipSupplyPages = [
        ['label' => 'Provision And Bond Store', 'url' => '/ship-supply/provision-and-bond-store', 'sort_order' => 0],
        ['label' => 'Deck And Engine Stores', 'url' => '/ship-supply/deck-and-engine-stores', 'sort_order' => 1],
        ['label' => 'Spare Parts Supply & Export', 'url' => '/ship-supply/spare-parts-supply-export', 'sort_order' => 2],
        ['label' => 'Cabin, Salon & galley Stores', 'url' => '/ship-supply/cabin-salon-galley-stores', 'sort_order' => 3],
        ['label' => 'Marine Paints & Chemicals', 'url' => '/ship-supply/marine-paints-chemicals', 'sort_order' => 4],
        ['label' => 'Gases and Oxygen Supply', 'url' => '/ship-supply/gases-and-oxygen-supply', 'sort_order' => 5],
        ['label' => 'Marine Lubes and Greases', 'url' => '/ship-supply/marine-lubes-and-greases', 'sort_order' => 6],
        ['label' => 'Chains – Ropes – Shackles', 'url' => '/ship-supply/chains-ropes-shackles', 'sort_order' => 7],
        ['label' => 'Safety Equipment', 'url' => '/ship-supply/safety-equipment', 'sort_order' => 8],
        ['label' => 'Navigation Equipment', 'url' => '/ship-supply/navigation-equipment', 'sort_order' => 9],
        ['label' => 'BA Chart – Publication', 'url' => '/ship-supply/ba-chart-publication', 'sort_order' => 10],
    ];

    /**
     * Preserve admin page sections by renaming legacy URLs in place.
     *
     * @var array<string, array{0: string, 1: string, 2: int}>
     */
    private array $legacyUrlMap = [
        '/ship-supply/provision-bond-stores' => ['/ship-supply/provision-and-bond-store', 'Provision And Bond Store', 0],
        '/ship-supply/general-store-supply' => ['/ship-supply/deck-and-engine-stores', 'Deck And Engine Stores', 1],
        '/ship-supply/spare-parts' => ['/ship-supply/spare-parts-supply-export', 'Spare Parts Supply & Export', 2],
        '/ship-supply/marine-paints' => ['/ship-supply/marine-paints-chemicals', 'Marine Paints & Chemicals', 4],
        '/ship-supply/gases-chemical' => ['/ship-supply/gases-and-oxygen-supply', 'Gases and Oxygen Supply', 5],
        '/ship-supply/lubricant-oil' => ['/ship-supply/marine-lubes-and-greases', 'Marine Lubes and Greases', 6],
        '/ship-supply/heavy-equipment' => ['/ship-supply/chains-ropes-shackles', 'Chains – Ropes – Shackles', 7],
        '/ship-supply/medical-stores' => ['/ship-supply/safety-equipment', 'Safety Equipment', 8],
        '/ship-supply/electronics-navigation-equipment' => ['/ship-supply/navigation-equipment', 'Navigation Equipment', 9],
    ];

    public function up(): void
    {
        $this->hideLocationsMenu();

        $shipSupply = $this->resolveShipSupplyMenu();

        if (! $shipSupply) {
            return;
        }

        $shipSupply->update([
            'label' => 'SHIP SUPPLY',
            'url' => '/ship-supply',
            'sort_order' => 15,
            'is_active' => true,
        ]);

        foreach ($this->legacyUrlMap as $oldUrl => [$newUrl, $label, $sortOrder]) {
            SubMenu::query()
                ->where('menu_id', $shipSupply->id)
                ->where(function ($q) use ($oldUrl): void {
                    $q->where('url', $oldUrl)->orWhere('url', ltrim($oldUrl, '/'));
                })
                ->update([
                    'label' => $label,
                    'url' => $newUrl,
                    'sort_order' => $sortOrder,
                    'is_active' => true,
                ]);
        }

        foreach ($this->shipSupplyPages as $page) {
            $existing = SubMenu::query()
                ->where('menu_id', $shipSupply->id)
                ->where(function ($q) use ($page): void {
                    $q->where('url', $page['url'])
                        ->orWhere('url', ltrim($page['url'], '/'));
                })
                ->first();

            if ($existing) {
                $existing->update([
                    'label' => $page['label'],
                    'sort_order' => $page['sort_order'],
                    'is_active' => true,
                ]);

                continue;
            }

            SubMenu::query()->create([
                'menu_id' => $shipSupply->id,
                'label' => $page['label'],
                'url' => $page['url'],
                'sort_order' => $page['sort_order'],
                'is_active' => true,
            ]);
        }

        $activeUrls = collect($this->shipSupplyPages)
            ->pluck('url')
            ->flatMap(fn (string $url) => [$url, ltrim($url, '/')])
            ->unique()
            ->all();

        SubMenu::query()
            ->where('menu_id', $shipSupply->id)
            ->whereNull('parent_sub_menu_id')
            ->whereNotIn('url', $activeUrls)
            ->update(['is_active' => false]);
    }

    public function down(): void
    {
        Menu::query()
            ->where(function ($q): void {
                $q->where('url', '/locations')
                    ->orWhereRaw('LOWER(label) LIKE ?', ['%locations%']);
            })
            ->update(['is_active' => true]);
    }

    private function hideLocationsMenu(): void
    {
        Menu::query()
            ->where(function ($q): void {
                $q->where('url', '/locations')
                    ->orWhere('url', 'locations')
                    ->orWhereRaw('LOWER(label) LIKE ?', ['%locations%']);
            })
            ->update(['is_active' => false]);
    }

    private function resolveShipSupplyMenu(): ?Menu
    {
        return Menu::query()
            ->where(function ($q): void {
                $q->where('url', '/ship-supply')
                    ->orWhere('url', 'ship-supply')
                    ->orWhereRaw('LOWER(label) LIKE ?', ['%ship supply%']);
            })
            ->orderBy('id')
            ->first();
    }
};
