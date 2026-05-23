<?php

use App\Models\HomeSection;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (HomeSection::query()->where('block_type', 'logo_carousel')->exists()) {
            return;
        }

        $recruitment = HomeSection::query()
            ->where('block_type', 'two_column')
            ->where('two_column_mode', 'split_cta')
            ->orderBy('sort_order')
            ->first();

        $insertOrder = $recruitment
            ? (int) $recruitment->sort_order + 1
            : (int) (HomeSection::query()->max('sort_order') ?? 0) + 1;

        HomeSection::query()
            ->where('sort_order', '>=', $insertOrder)
            ->orderByDesc('sort_order')
            ->get()
            ->each(function (HomeSection $section) {
                $section->update(['sort_order' => $section->sort_order + 1]);
            });

        HomeSection::create([
            'block_type' => 'logo_carousel',
            'variant' => 'certificates',
            'title' => 'Quality Certificates & Memberships',
            'description' => 'Click to see all our',
            'button_label' => 'Quality Certificates & Memberships',
            'button_url' => '#',
            'data' => [
                'items' => [
                    ['path' => null, 'title' => 'TURSSA', 'url' => null],
                    ['path' => null, 'title' => 'ISO 22000:2018', 'url' => null],
                    ['path' => null, 'title' => 'ISO 14001:2015', 'url' => null],
                    ['path' => null, 'title' => 'ISO 45001', 'url' => null],
                ],
            ],
            'sort_order' => $insertOrder,
            'is_active' => true,
        ]);
    }

    public function down(): void
    {
        HomeSection::query()
            ->where('block_type', 'logo_carousel')
            ->each(function (HomeSection $section) {
                $order = (int) $section->sort_order;
                $section->delete();

                HomeSection::query()
                    ->where('sort_order', '>', $order)
                    ->decrement('sort_order');
            });
    }
};
