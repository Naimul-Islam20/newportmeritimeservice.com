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

        $sub = SubMenu::query()
            ->where(function ($q) use ($path, $pathAlt): void {
                $q->where('url', $path)->orWhere('url', $pathAlt);
            })
            ->where('is_active', true)
            ->first();

        if ($sub) {
            $cover = $sub->coverImageUrl();

            return view('site.pages.menu-page', [
                'title' => SiteDetail::pageTitle($sub->label),
                'metaDescription' => $sub->description ?: null,
                'heading' => $sub->label,
                'lead' => $sub->description ?: null,
                'heroImageUrl' => $cover !== '' ? $cover : null,
                'pageContent' => $sub->page_content,
                'pageSections' => $sub->pageSections()->ordered()->where('is_active', true)->get(),
            ]);
        }

        $menu = Menu::query()
            ->where(function ($q) use ($path, $pathAlt): void {
                $q->where('url', $path)->orWhere('url', $pathAlt);
            })
            ->where('is_active', true)
            ->first();

        abort_if(! $menu, 404);

        return view('site.pages.menu-page', [
            'title' => SiteDetail::pageTitle($menu->label),
            'metaDescription' => $menu->description ?: null,
            'heading' => $menu->label,
            'lead' => $menu->description ?: null,
            'pageContent' => $menu->page_content,
            'pageSections' => $menu->pageSections()->ordered()->where('is_active', true)->get(),
            'submenuPaginator' => $menu->submenuPaginatorForPublicParentPage(),
        ]);
    }
}
