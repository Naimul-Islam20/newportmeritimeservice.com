<?php

use App\Models\Menu;
use App\Models\ServicePage;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Menu::query()
            ->where(function ($q): void {
                $q->where('url', '/ship-supply')
                    ->orWhere('url', 'ship-supply');
            })
            ->update([
                'show_submenus_on_page' => true,
            ]);

        ServicePage::query()
            ->where('slug', 'provision')
            ->update(['is_active' => false]);
    }

    public function down(): void
    {
        Menu::query()
            ->where(function ($q): void {
                $q->where('url', '/ship-supply')
                    ->orWhere('url', 'ship-supply');
            })
            ->update([
                'show_submenus_on_page' => false,
            ]);
    }
};
