<?php

use App\Models\Menu;
use App\Models\SubMenu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $award = Menu::query()
            ->where(function ($q): void {
                $q->where('url', '/award')
                    ->orWhere('url', 'award')
                    ->orWhereRaw('LOWER(label) LIKE ?', ['%award%']);
            })
            ->first();

        if (! $award) {
            return;
        }

        $items = [
            [
                'label' => 'Certifications & Membership',
                'url' => '/quality-certificates-memberships',
                'sort_order' => 0,
            ],
            [
                'label' => 'Honorable Client',
                'url' => '/award/honorable-client',
                'sort_order' => 1,
            ],
        ];

        foreach ($items as $item) {
            SubMenu::query()->updateOrCreate(
                [
                    'menu_id' => $award->id,
                    'parent_sub_menu_id' => null,
                    'url' => $item['url'],
                ],
                [
                    'label' => $item['label'],
                    'sort_order' => $item['sort_order'],
                    'is_active' => true,
                ],
            );
        }
    }

    public function down(): void
    {
        $award = Menu::query()
            ->where(function ($q): void {
                $q->where('url', '/award')
                    ->orWhere('url', 'award')
                    ->orWhereRaw('LOWER(label) LIKE ?', ['%award%']);
            })
            ->first();

        if (! $award) {
            return;
        }

        SubMenu::query()
            ->where('menu_id', $award->id)
            ->whereNull('parent_sub_menu_id')
            ->whereIn('url', [
                '/quality-certificates-memberships',
                '/award/honorable-client',
            ])
            ->delete();
    }
};
