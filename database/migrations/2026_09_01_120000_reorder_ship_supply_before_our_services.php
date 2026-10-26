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

        $ourServicesMenu = Menu::query()
            ->where('is_active', true)
            ->where(function ($q): void {
                $q->where('url', '/our-services')
                    ->orWhere('url', 'our-services');
            })
            ->first();

        $shipSupplySection = $shipSupplyMenu
            ? HomeSection::query()
                ->where('block_type', 'carousel')
                ->where('variant', 'simple')
                ->where('menu_id', $shipSupplyMenu->id)
                ->first()
            : null;

        $ourServicesSection = $ourServicesMenu
            ? HomeSection::query()
                ->where('block_type', 'carousel')
                ->where('variant', 'simple')
                ->where('menu_id', $ourServicesMenu->id)
                ->first()
            : null;

        if ($shipSupplySection && $ourServicesSection) {
            $shipSupplySection->update(['sort_order' => 1]);
            $ourServicesSection->update(['sort_order' => 2]);
        }
    }

    public function down(): void
    {
        $shipSupplyMenu = Menu::query()
            ->where('is_active', true)
            ->where(function ($q): void {
                $q->where('url', '/ship-supply')
                    ->orWhere('url', 'ship-supply');
            })
            ->first();

        $ourServicesMenu = Menu::query()
            ->where('is_active', true)
            ->where(function ($q): void {
                $q->where('url', '/our-services')
                    ->orWhere('url', 'our-services');
            })
            ->first();

        $shipSupplySection = $shipSupplyMenu
            ? HomeSection::query()
                ->where('block_type', 'carousel')
                ->where('variant', 'simple')
                ->where('menu_id', $shipSupplyMenu->id)
                ->first()
            : null;

        $ourServicesSection = $ourServicesMenu
            ? HomeSection::query()
                ->where('block_type', 'carousel')
                ->where('variant', 'simple')
                ->where('menu_id', $ourServicesMenu->id)
                ->first()
            : null;

        if ($shipSupplySection && $ourServicesSection) {
            $shipSupplySection->update(['sort_order' => 2]);
            $ourServicesSection->update(['sort_order' => 1]);
        }
    }
};
