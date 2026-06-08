<?php

use App\Models\HomeServiceAreaSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $setting = HomeServiceAreaSetting::query()->first();
        if (! $setting) {
            $setting = HomeServiceAreaSetting::create(HomeServiceAreaSetting::defaultAttributes());
        }

        $stored = is_array($setting->branches_items) ? $setting->branches_items : [];
        $hasPortLabels = collect($stored)
            ->filter(fn ($item) => is_array($item) && filled($item['label'] ?? null))
            ->isNotEmpty();

        if ($hasPortLabels) {
            return;
        }

        $setting->update([
            'branches_view_all_url' => '/contact',
            'branches_items' => HomeServiceAreaSetting::defaultBranchItemsStored(),
        ]);
    }

    public function down(): void
    {
        // Non-destructive: keep admin-edited port carousel data.
    }
};
