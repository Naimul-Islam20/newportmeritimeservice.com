<?php

use App\Models\HomeSection;
use App\Models\Menu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $shipSupplyMenu = Menu::query()
            ->where('is_active', true)
            ->where(function ($q): void {
                $q->where('url', '/ship-supply')
                    ->orWhere('url', 'ship-supply');
            })
            ->first();

        if (! $shipSupplyMenu) {
            return;
        }

        $alreadyExists = HomeSection::query()
            ->where('block_type', 'carousel')
            ->where('variant', 'simple')
            ->where('menu_id', $shipSupplyMenu->id)
            ->exists();

        if ($alreadyExists) {
            return;
        }

        $ourServicesMenu = Menu::query()
            ->where('is_active', true)
            ->where(function ($q): void {
                $q->where('url', '/our-services')
                    ->orWhere('url', 'our-services');
            })
            ->first();

        $ourServicesSection = $ourServicesMenu
            ? HomeSection::query()
                ->where('block_type', 'carousel')
                ->where('variant', 'simple')
                ->where('menu_id', $ourServicesMenu->id)
                ->orderBy('sort_order')
                ->first()
            : HomeSection::query()
                ->where('block_type', 'carousel')
                ->where('variant', 'simple')
                ->orderBy('sort_order')
                ->first();

        $insertOrder = $ourServicesSection
            ? (int) $ourServicesSection->sort_order + 1
            : (int) (HomeSection::query()->max('sort_order') ?? 0) + 1;

        HomeSection::query()
            ->where('sort_order', '>=', $insertOrder)
            ->orderByDesc('sort_order')
            ->get()
            ->each(function (HomeSection $section): void {
                $section->update(['sort_order' => $section->sort_order + 1]);
            });

        HomeSection::create([
            'block_type' => 'carousel',
            'variant' => 'simple',
            'menu_id' => $shipSupplyMenu->id,
            'mini_title' => 'Ship Supply',
            'title' => 'Supplies',
            'sort_order' => $insertOrder,
            'is_active' => true,
        ]);
    }

    public function down(): void
    {
        $shipSupplyMenu = Menu::query()
            ->where(function ($q): void {
                $q->where('url', '/ship-supply')
                    ->orWhere('url', 'ship-supply');
            })
            ->first();

        if (! $shipSupplyMenu) {
            return;
        }

        HomeSection::query()
            ->where('block_type', 'carousel')
            ->where('variant', 'simple')
            ->where('menu_id', $shipSupplyMenu->id)
            ->each(function (HomeSection $section): void {
                $order = (int) $section->sort_order;
                $section->delete();

                HomeSection::query()
                    ->where('sort_order', '>', $order)
                    ->decrement('sort_order');
            });
    }
};
