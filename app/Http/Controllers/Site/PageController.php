<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use App\Models\Menu;
use App\Models\SubMenu;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    private function menuPageIfExists(Request $request): ?View
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
            return view('site.pages.menu-page', [
                'title' => $sub->label.' — '.config('app.name'),
                'metaDescription' => $sub->description ?: null,
                'heading' => $sub->label,
                'lead' => $sub->description ?: null,
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

        if (! $menu) {
            return null;
        }

        return view('site.pages.menu-page', [
            'title' => $menu->label.' — '.config('app.name'),
            'metaDescription' => $menu->description ?: null,
            'heading' => $menu->label,
            'lead' => $menu->description ?: null,
            'pageContent' => $menu->page_content,
            'pageSections' => $menu->pageSections()->ordered()->where('is_active', true)->get(),
            'submenuPaginator' => $menu->submenuPaginatorForPublicParentPage(),
        ]);
    }

    public function shipSupply(Request $request): View
    {
        if ($v = $this->menuPageIfExists($request)) {
            return $v;
        }

        return view('site.pages.ship-supply', [
            'title' => 'Ship Supply — '.config('app.name'),
            'metaDescription' => 'Provisions, stores, and deck supplies for vessels and port operations.',
        ]);
    }

    public function ourServices(Request $request): View
    {
        if ($v = $this->menuPageIfExists($request)) {
            return $v;
        }

        return view('site.pages.simple', [
            'title' => 'Our Services — '.config('app.name'),
            'metaDescription' => 'Maritime logistics, documentation, and operational support services.',
            'heading' => 'Our Services',
            'lead' => 'From berth coordination to stakeholder communication, we support the full lifecycle of your port call.',
        ]);
    }

    public function aboutUs(): View
    {
        $about = AboutPage::resolvedForPublic();

        return view('site.pages.about-us', [
            'title' => $about->hero_title.' — '.config('app.name'),
            'metaDescription' => $about->meta_description,
            'about' => $about,
        ]);
    }

    public function whereWeAre(Request $request): View
    {
        if ($v = $this->menuPageIfExists($request)) {
            return $v;
        }

        return view('site.pages.where-we-are', [
            'title' => 'Where We Are — '.config('app.name'),
            'metaDescription' => 'Our service areas and locations across the region.',
        ]);
    }

    public function award(Request $request): View
    {
        if ($v = $this->menuPageIfExists($request)) {
            return $v;
        }

        return view('site.pages.simple', [
            'title' => 'Award — '.config('app.name'),
            'metaDescription' => 'Recognition and milestones from our partners and the industry.',
            'heading' => 'Award',
            'lead' => 'We are proud of the trust our clients place in us. Details and highlights will appear here as we update this section.',
        ]);
    }
}
