<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\SiteDetail;
use App\Models\SubMenu;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuPageController extends Controller
{
    public function show(Request $request): View
    {
        $rawPath = $request->path();
        $path = $rawPath === '' ? '/' : '/'.ltrim($rawPath, '/');
        $path = rtrim($path, '/') === '' ? '/' : rtrim($path, '/');
        $pathAlt = ltrim($path, '/');

        $blogNavMenu = null;

        $sub = SubMenu::query()
            ->where(function ($q) use ($path, $pathAlt): void {
                $q->where('url', $path)->orWhere('url', $pathAlt);
            })
            ->where('is_active', true)
            ->with(['menu', 'parent', 'children' => fn ($q) => $q->active()->ordered()])
            ->first();

        if ($sub) {
            $cover = $sub->coverImageUrl();

            if ($sub->menu && $sub->menu->normalizedPath() === '/blog') {
                $blogNavMenu = $sub->menu->relationLoaded('subMenus')
                    ? $sub->menu
                    : $sub->menu()->with(['subMenus' => fn ($q) => $q->active()->whereNull('parent_sub_menu_id')->ordered()])->first();

                $categorySub = $sub;
                $currentArticle = $sub;

                if ($sub->parent && $sub->parent->menu && $sub->parent->menu->id === $sub->menu->id) {
                    $categorySub = $sub->parent;
                } elseif ($sub->children->isNotEmpty()) {
                    $currentArticle = $sub->children->first();
                    $categorySub = $sub;
                }

                $categoryPath = $categorySub->normalizedPath();
                $isNewsCategory = $categoryPath === '/blog/news';

                $articles = $isNewsCategory
                    ? $categorySub->children()->active()->ordered()->get()
                    : collect();

                if ($articles->isNotEmpty()) {
                    if (! $articles->contains('id', $currentArticle->id)) {
                        $currentArticle = $articles->first();
                    }

                    return view('site.pages.blog-category', [
                        'title' => SiteDetail::pageTitle('Gimas Blog'),
                        'metaDescription' => $sub->description ?: null,
                        'heading' => $categorySub->label,
                        'lead' => $categorySub->description ?: null,
                        'heroImageUrl' => $cover !== '' ? $cover : null,
                        'blogNavMenu' => $blogNavMenu,
                        'categorySub' => $categorySub,
                        'articles' => $articles,
                        'currentArticle' => $currentArticle,
                    ]);
                }
            }

            return view('site.pages.menu-page', [
                'title' => SiteDetail::pageTitle($sub->label),
                'metaDescription' => $sub->description ?: null,
                'heading' => $sub->label,
                'lead' => $sub->description ?: null,
                'heroImageUrl' => $cover !== '' ? $cover : null,
                'pageContent' => $sub->page_content,
                'pageSections' => $sub->pageSections()->ordered()->where('is_active', true)->get(),
                'blogNavMenu' => $blogNavMenu,
            ]);
        }

        $menu = Menu::query()
            ->where(function ($q) use ($path, $pathAlt): void {
                $q->where('url', $path)->orWhere('url', $pathAlt);
            })
            ->where('is_active', true)
            ->first();

        abort_if(! $menu, 404);

        if ($menu->normalizedPath() === '/blog') {
            $blogNavMenu = $menu->relationLoaded('subMenus') ? $menu : Menu::blogMenu();
        }

        return view('site.pages.menu-page', [
            'title' => SiteDetail::pageTitle($menu->label),
            'metaDescription' => $menu->description ?: null,
            'heading' => $menu->label,
            'lead' => $menu->description ?: null,
            'pageContent' => $menu->page_content,
            'pageSections' => $menu->pageSections()->ordered()->where('is_active', true)->get(),
            'submenuPaginator' => $menu->submenuPaginatorForPublicParentPage(),
            'blogNavMenu' => $blogNavMenu,
        ]);
    }
}
