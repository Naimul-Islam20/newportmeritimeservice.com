<?php

use App\Models\Menu;
use App\Models\SubMenu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $blog = Menu::query()
            ->where(function ($q): void {
                $q->where('url', '/blog')->orWhere('url', 'blog');
            })
            ->first();

        if (! $blog) {
            return;
        }

        SubMenu::query()
            ->where('menu_id', $blog->id)
            ->whereNull('parent_sub_menu_id')
            ->where(function ($q): void {
                $q->where('url', '/blog')->orWhere('url', 'blog');
            })
            ->update(['is_active' => false]);

        SubMenu::query()
            ->where('menu_id', $blog->id)
            ->get()
            ->each(function (SubMenu $sub) use ($blog): void {
                $path = $sub->normalizedPath();

                if ($path === null) {
                    return;
                }

                if ($path === '/blog') {
                    $sub->update(['is_active' => false, 'parent_sub_menu_id' => null]);

                    return;
                }

                $parentId = SubMenu::resolveBlogParentSubMenuId($blog, (string) $sub->url, $sub->parent_sub_menu_id);

                if ((int) $sub->parent_sub_menu_id !== (int) $parentId) {
                    $sub->update(['parent_sub_menu_id' => $parentId]);
                }
            });
    }

    public function down(): void
    {
        // Data repair only — no rollback.
    }
};
