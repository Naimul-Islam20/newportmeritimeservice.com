<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\SiteDetail;
use App\Models\SubMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class MenuPageController extends Controller
{
    /**
     * @return Collection<int, SubMenu>
     */
    private function categoryArticles(SubMenu $categorySub): Collection
    {
        return $categorySub->categoryItems(repairOrphans: true);
    }

    /**
     * @return list<string>
     */
    private function blogSidebarTags(SubMenu $categorySub): array
    {
        if ($categorySub->blogLayoutType() !== 'sidebar_article') {
            return [];
        }

        $tags = config('blog.sidebar_tags', []);

        return is_array($tags) ? array_values(array_filter($tags, fn ($t) => is_string($t) && $t !== '')) : [];
    }

    private function rememberRecentlyViewed(SubMenu $categorySub, SubMenu $article): void
    {
        $key = 'blog_recent_'.$categorySub->id;
        $ids = array_values(array_filter(
            session($key, []),
            fn ($id) => (int) $id !== (int) $article->id,
        ));
        array_unshift($ids, $article->id);
        session([$key => array_slice($ids, 0, 3)]);
    }

    /**
     * @return Collection<int, SubMenu>
     */
    private function recentlyViewedArticles(SubMenu $categorySub, ?SubMenu $exclude = null): Collection
    {
        $ids = session('blog_recent_'.$categorySub->id, []);

        if ($ids === []) {
            return collect();
        }

        $posts = SubMenu::query()
            ->whereIn('id', $ids)
            ->where('parent_sub_menu_id', $categorySub->id)
            ->active()
            ->get()
            ->keyBy('id');

        return collect($ids)
            ->map(fn ($id) => $posts->get((int) $id))
            ->filter()
            ->when($exclude, fn ($c) => $c->filter(fn (SubMenu $p) => (int) $p->id !== (int) $exclude->id))
            ->values();
    }

    private function renderBlogCategoryIndex(SubMenu $categorySub, ?Menu $blogNavMenu): View
    {
        $categoryCover = $categorySub->coverImageUrl();
        $articles = $this->categoryArticles($categorySub);
        $featuredArticle = $articles->first();

        if ($featuredArticle && $categorySub->blogLayoutType() === 'sidebar_article') {
            $this->rememberRecentlyViewed($categorySub, $featuredArticle);
        }

        return view('site.pages.blog-category-index', [
            'title' => SiteDetail::pageTitle($categorySub->label),
            'metaDescription' => $categorySub->description ?: null,
            'heading' => $categorySub->label,
            'lead' => $categorySub->description ?: null,
            'heroImageUrl' => $categoryCover !== '' ? $categoryCover : null,
            'pageContent' => $categorySub->page_content,
            'pageSections' => $categorySub->pageSections()->ordered()->where('is_active', true)->get(),
            'blogNavMenu' => $blogNavMenu,
            'categorySub' => $categorySub,
            'layoutType' => $categorySub->blogLayoutType(),
            'articles' => $articles,
            'featuredArticle' => $featuredArticle,
            'recentlyViewed' => $featuredArticle
                ? $this->recentlyViewedArticles($categorySub, $featuredArticle)
                : collect(),
            'blogTags' => $this->blogSidebarTags($categorySub),
        ]);
    }

    private function renderBlogArticle(SubMenu $article, SubMenu $categorySub, ?Menu $blogNavMenu): View
    {
        $article->repairBlogPostUrlAndParent();
        $this->rememberRecentlyViewed($categorySub, $article);
        $categoryCover = $categorySub->coverImageUrl();

        return view('site.pages.blog-category', [
            'title' => SiteDetail::pageTitle($article->label),
            'metaDescription' => $article->description ?: null,
            'heading' => $categorySub->label,
            'lead' => $categorySub->description ?: null,
            'heroImageUrl' => $categoryCover !== '' ? $categoryCover : null,
            'blogNavMenu' => $blogNavMenu,
            'categorySub' => $categorySub,
            'layoutType' => $categorySub->blogLayoutType(),
            'currentArticle' => $article,
            'sidebarItems' => $this->categoryArticles($categorySub),
            'recentlyViewed' => $this->recentlyViewedArticles($categorySub, $article),
            'blogTags' => $this->blogSidebarTags($categorySub),
        ]);
    }

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

            if ($sub->menu && SubMenu::isBlogMenu($sub->menu)) {
                $blogNavMenu = Menu::withHeaderNavSubMenus(
                    $sub->menu->relationLoaded('subMenus')
                        ? $sub->menu
                        : $sub->menu()->with(['subMenus' => fn ($q) => $q->active()->whereNull('parent_sub_menu_id')->ordered()])->first()
                );

                $categorySub = $sub->blogCategoryForPost() ?? $sub;

                if ($categorySub->isNavDropdownCategory()) {
                    if ($sub->isBlogCategoryPost()) {
                        return $this->renderBlogArticle($sub, $categorySub, $blogNavMenu);
                    }

                    return $this->renderBlogCategoryIndex($categorySub, $blogNavMenu);
                }

                if ($sub->isBlogCategoryPost()) {
                    $categorySub = $sub->blogCategoryForPost();
                    if ($categorySub) {
                        return $this->renderBlogArticle($sub, $categorySub, $blogNavMenu);
                    }
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
            $blogNavMenu = Menu::withHeaderNavSubMenus(
                $menu->relationLoaded('subMenus') ? $menu : Menu::blogMenu()
            );
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
