<?php

use App\Models\Menu;
use App\Models\SubMenu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * @var list<array{label: string, url: string, sort_order: int}>
     */
    private array $awardItems = [
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
            $award = Menu::query()->create([
                'label' => 'AWARD',
                'url' => '/award',
                'sort_order' => 25,
                'is_active' => true,
            ]);
        } else {
            $award->update([
                'label' => 'AWARD',
                'url' => '/award',
                'is_active' => true,
            ]);
        }

        foreach ($this->awardItems as $item) {
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
        // Keep award submenu rows — they are also used by the header dropdown.
    }
};
