<?php

use App\Models\CareerPage;
use App\Models\Menu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (! CareerPage::query()->exists()) {
            CareerPage::query()->create(CareerPage::defaultContent());
        }

        Menu::query()
            ->where(function ($q): void {
                $q->where('url', '/award')
                    ->orWhere('url', 'award')
                    ->orWhereRaw('LOWER(label) LIKE ?', ['%award%']);
            })
            ->update(['is_active' => false]);

        $locations = Menu::query()
            ->where(function ($q): void {
                $q->where('url', '/locations')
                    ->orWhereRaw('LOWER(label) LIKE ?', ['%locations%']);
            })
            ->first();

        $sortOrder = $locations ? $locations->sort_order + 5 : 30;

        $career = Menu::query()
            ->where(function ($q): void {
                $q->where('url', '/career')
                    ->orWhere('url', 'career')
                    ->orWhereRaw('LOWER(label) LIKE ?', ['%career%']);
            })
            ->first();

        if (! $career) {
            Menu::create([
                'label' => 'CAREER',
                'url' => '/career',
                'sort_order' => $sortOrder,
                'is_active' => true,
            ]);
        } else {
            $career->update([
                'label' => 'CAREER',
                'url' => '/career',
                'sort_order' => $sortOrder,
                'is_active' => true,
            ]);
        }
    }

    public function down(): void
    {
        // Keep data on rollback.
    }
};
