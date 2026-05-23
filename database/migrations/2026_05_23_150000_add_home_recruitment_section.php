<?php

use App\Models\HomeSection;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $exists = HomeSection::query()
            ->where('block_type', 'two_column')
            ->where('two_column_mode', 'split_cta')
            ->exists();

        if ($exists) {
            return;
        }

        $aboutSection = HomeSection::query()
            ->where('block_type', 'two_column')
            ->where('two_column_mode', 'image_details')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->first();

        $insertOrder = $aboutSection
            ? (int) $aboutSection->sort_order + 1
            : (int) (HomeSection::query()->max('sort_order') ?? 0) + 1;

        HomeSection::query()
            ->where('sort_order', '>=', $insertOrder)
            ->orderByDesc('sort_order')
            ->get()
            ->each(function (HomeSection $section) {
                $section->update(['sort_order' => $section->sort_order + 1]);
            });

        HomeSection::create([
            'block_type' => 'two_column',
            'variant' => 'recruitment',
            'two_column_mode' => 'split_cta',
            'layout_width' => 'full',
            'title' => 'Recruitment',
            'description' => 'To be the most preferred maritime service company with innovative and sustainable practices that make our people feel valued and united by a shared culture of excellence.',
            'button_label' => 'JOIN US',
            'button_url' => '/contact',
            'data' => [
                'secondary_description' => 'For open positions, please visit our careers page or send your CV by email: <strong>careers@newportmeritimeservice.com</strong>',
                'secondary_button_label' => 'OUR TEAM',
                'secondary_button_url' => '/about-us',
            ],
            'sort_order' => $insertOrder,
            'is_active' => true,
        ]);
    }

    public function down(): void
    {
        HomeSection::query()
            ->where('block_type', 'two_column')
            ->where('two_column_mode', 'split_cta')
            ->each(function (HomeSection $section) {
                $order = (int) $section->sort_order;
                $section->delete();

                HomeSection::query()
                    ->where('sort_order', '>', $order)
                    ->decrement('sort_order');
            });
    }
};
