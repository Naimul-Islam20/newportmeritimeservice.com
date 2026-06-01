<?php

use App\Models\ServicePage;
use App\Models\ServiceSidebarSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        ServiceSidebarSetting::query()->firstOrCreate([], ServiceSidebarSetting::defaultContent());

        $pages = [
            'technical-stores',
            'provision',
            'what-we-do',
            'transit-delivery',
            'port-delivery',
            'operations-logistics',
        ];

        foreach ($pages as $slug) {
            $defaults = ServicePage::defaultForSlug($slug);
            ServicePage::query()->updateOrCreate(
                ['slug' => $slug],
                array_merge($defaults, ['slug' => $slug, 'is_active' => true]),
            );
        }
    }

    public function down(): void
    {
        // Keep CMS data on rollback.
    }
};
