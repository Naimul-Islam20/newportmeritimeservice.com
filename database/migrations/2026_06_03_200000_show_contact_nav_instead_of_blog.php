<?php

use App\Models\Menu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Menu::query()
            ->where(function ($q): void {
                $q->where('url', '/blog')
                    ->orWhere('url', 'blog')
                    ->orWhereRaw('LOWER(label) LIKE ?', ['%blog%']);
            })
            ->update(['is_active' => false]);

        Menu::query()->updateOrCreate(
            ['url' => '/contact'],
            [
                'label' => 'CONTACT',
                'sort_order' => 40,
                'is_active' => true,
            ],
        );
    }

    public function down(): void
    {
        Menu::query()
            ->where(function ($q): void {
                $q->where('url', '/contact')
                    ->orWhere('url', 'contact');
            })
            ->whereDoesntHave('subMenus')
            ->update(['is_active' => false]);

        Menu::query()
            ->where(function ($q): void {
                $q->where('url', '/blog')
                    ->orWhere('url', 'blog')
                    ->orWhereRaw('LOWER(label) LIKE ?', ['%blog%']);
            })
            ->update([
                'label' => 'BLOG',
                'sort_order' => 40,
                'is_active' => true,
            ]);
    }
};
