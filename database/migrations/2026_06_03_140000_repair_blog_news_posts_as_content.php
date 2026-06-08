<?php

use App\Models\Menu;
use App\Models\SubMenu;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

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

        $news = SubMenu::query()
            ->where('menu_id', $blog->id)
            ->whereNull('parent_sub_menu_id')
            ->where(function ($q): void {
                $q->where('url', '/blog/news')->orWhere('url', 'blog/news');
            })
            ->first();

        if (! $news) {
            return;
        }

        $categoryPaths = ['/blog/news', '/blog/events', '/blog/gallery', '/blog/recipes', '/blog/newport-tv', '/blog'];

        SubMenu::query()
            ->where('menu_id', $blog->id)
            ->where('id', '!=', $news->id)
            ->where(function ($q) use ($categoryPaths): void {
                foreach ($categoryPaths as $path) {
                    $q->where('url', '!=', $path)->where('url', '!=', ltrim($path, '/'));
                }
            })
            ->get()
            ->each(function (SubMenu $post) use ($news): void {
                if ($post->isNavDropdownCategory()) {
                    return;
                }

                $path = $post->normalizedPath();
                if ($path === null) {
                    return;
                }

                $isNewsPost = (int) $post->parent_sub_menu_id === (int) $news->id
                    || preg_match('#^/blog/news/[^/]+$#', $path) === 1
                    || (preg_match('#^/blog/[^/]+$#', $path) === 1 && (int) $post->parent_sub_menu_id === 0);

                if (! $isNewsPost) {
                    return;
                }

                if ((int) $post->parent_sub_menu_id !== (int) $news->id) {
                    $post->parent_sub_menu_id = $news->id;
                }

                if (! preg_match('#^/blog/news/[^/]+$#', $path)) {
                    $slug = Str::slug($post->label) ?: 'item';
                    $candidate = '/blog/news/'.$slug;
                    $i = 2;
                    while (SubMenu::query()->where('url', $candidate)->where('id', '!=', $post->id)->exists()) {
                        $candidate = '/blog/news/'.$slug.'-'.$i;
                        $i++;
                    }
                    $post->url = $candidate;
                }

                if ($post->isDirty()) {
                    $post->save();
                }
            });
    }

    public function down(): void
    {
        // Data repair only.
    }
};
