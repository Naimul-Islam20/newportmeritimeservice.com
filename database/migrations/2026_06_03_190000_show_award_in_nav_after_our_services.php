<?php

use App\Models\Menu;
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

        if ($award) {
            $award->update([
                'label' => 'AWARD',
                'url' => '/award',
                'sort_order' => 25,
                'is_active' => true,
            ]);
        } else {
            Menu::query()->create([
                'label' => 'AWARD',
                'url' => '/award',
                'sort_order' => 25,
                'is_active' => true,
            ]);
        }
    }

    public function down(): void
    {
        Menu::query()
            ->where(function ($q): void {
                $q->where('url', '/award')
                    ->orWhere('url', 'award')
                    ->orWhereRaw('LOWER(label) LIKE ?', ['%award%']);
            })
            ->update(['is_active' => false]);

        Menu::query()
            ->where(function ($q): void {
                $q->where('url', '/career')
                    ->orWhere('url', 'career')
                    ->orWhereRaw('LOWER(label) LIKE ?', ['%career%']);
            })
            ->update(['sort_order' => 30]);
    }
};
