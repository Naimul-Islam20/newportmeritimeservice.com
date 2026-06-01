<?php

use App\Models\Menu;
use App\Models\SubMenu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * @var list<array{label: string, url: string, sort_order: int}>
     */
    private array $locationLinks = [
        ['label' => 'Overview', 'url' => '/locations', 'sort_order' => 0],
        ['label' => 'All Ports of Turkey', 'url' => '/locations/all-ports-of-turkey', 'sort_order' => 1],
        ['label' => 'Ports in the ARA area', 'url' => '/locations/ports-in-the-ara-area', 'sort_order' => 2],
    ];

    public function up(): void
    {
        $ourServices = Menu::query()
            ->where(function ($q): void {
                $q->where('url', '/our-services')
                    ->orWhereRaw('LOWER(label) LIKE ?', ['%our services%']);
            })
            ->first();

        $sortOrder = $ourServices ? $ourServices->sort_order + 5 : 25;

        $menu = Menu::query()
            ->where(function ($q): void {
                $q->where('url', '/locations')
                    ->orWhereRaw('LOWER(label) LIKE ?', ['%locations%']);
            })
            ->first();

        if (! $menu) {
            $menu = Menu::create([
                'label' => 'LOCATIONS',
                'url' => '/locations',
                'sort_order' => $sortOrder,
                'is_active' => true,
            ]);
        } else {
            $menu->update([
                'label' => 'LOCATIONS',
                'url' => '/locations',
                'sort_order' => $sortOrder,
                'is_active' => true,
            ]);
        }

        $canonicalLabels = array_column($this->locationLinks, 'label');

        foreach ($this->locationLinks as $link) {
            SubMenu::query()->updateOrCreate(
                [
                    'menu_id' => $menu->id,
                    'label' => $link['label'],
                ],
                [
                    'url' => $link['url'],
                    'sort_order' => $link['sort_order'],
                    'is_active' => true,
                ]
            );
        }

        SubMenu::query()
            ->where('menu_id', $menu->id)
            ->whereNotIn('label', $canonicalLabels)
            ->delete();
    }

    public function down(): void
    {
        // Keep menu data on rollback.
    }
};
