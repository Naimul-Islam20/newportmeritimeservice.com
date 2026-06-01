<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use App\Models\CareerPage;
use App\Models\CeoMessagePage;
use App\Models\Menu;
use App\Models\OurStoryPage;
use App\Models\OurTeamPage;
use App\Models\ServicePage;
use App\Models\ServiceSidebarSetting;
use App\Models\SiteDetail;
use App\Models\SubMenu;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Paths with dedicated Blade + admin page models — do not render generic menu-page layout.
     *
     * @var list<string>
     */
    private function menuPageIfExists(Request $request): ?View
    {
        $rawPath = $request->path();
        $path = $rawPath === '' ? '/' : '/'.ltrim($rawPath, '/');
        $path = rtrim($path, '/') === '' ? '/' : rtrim($path, '/');
        $pathAlt = ltrim($path, '/');

        if (in_array($path, $this->dedicatedPagePaths(), true)) {
            return null;
        }

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

        if (! $menu) {
            return null;
        }

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

    public function shipSupply(Request $request): View
    {
        return $this->serviceDetailPage($request, 'provision');
    }

    public function technicalStores(Request $request): View
    {
        return $this->serviceDetailPage($request, 'technical-stores');
    }

    public function ourServices(Request $request): View
    {
        return $this->serviceDetailPage($request, 'what-we-do');
    }

    public function serviceTransitDelivery(Request $request): View
    {
        return $this->serviceDetailPage($request, 'transit-delivery');
    }

    public function servicePortDelivery(Request $request): View
    {
        return $this->serviceDetailPage($request, 'port-delivery');
    }

    public function serviceOperationsLogistics(Request $request): View
    {
        return $this->serviceDetailPage($request, 'operations-logistics');
    }

    private function serviceDetailPage(Request $request, string $slug): View
    {
        if ($v = $this->menuPageIfExists($request)) {
            return $v;
        }

        $page = ServicePage::resolvedForPublic($slug);
        $sidebar = ServiceSidebarSetting::resolvedForPublic($page->open_nav_group_id, $page->path);

        return view('site.pages.service-detail', [
            'page' => $page,
            'sidebar' => $sidebar,
        ]);
    }

    /**
     * @return list<string>
     */
    private function dedicatedPagePaths(): array
    {
        return array_values(array_unique(array_merge(
            [
                '/our-story',
                '/message-from-ceo',
                '/our-team-management',
                '/locations',
                '/career',
            ],
            ServicePage::dedicatedPaths(),
        )));
    }

    public function ourStory(Request $request): View
    {
        if ($v = $this->menuPageIfExists($request)) {
            return $v;
        }

        $story = OurStoryPage::resolvedForPublic();

        return view('site.pages.our-story', [
            'title' => SiteDetail::pageTitle($story->hero_title),
            'metaDescription' => $story->meta_description,
            'story' => $story,
        ]);
    }

    public function messageFromCeo(Request $request): View
    {
        if ($v = $this->menuPageIfExists($request)) {
            return $v;
        }

        $ceo = CeoMessagePage::resolvedForPublic();

        return view('site.pages.message-from-ceo', [
            'title' => SiteDetail::pageTitle('Message from the CEO'),
            'metaDescription' => $ceo->meta_description,
            'ceo' => $ceo,
        ]);
    }

    public function ourTeamManagement(Request $request): View
    {
        if ($v = $this->menuPageIfExists($request)) {
            return $v;
        }

        $team = OurTeamPage::resolvedForPublic();

        return view('site.pages.our-team-management', [
            'title' => SiteDetail::pageTitle($team->hero_title),
            'metaDescription' => $team->meta_description,
            'team' => $team,
        ]);
    }

    public function career(Request $request): View
    {
        if ($v = $this->menuPageIfExists($request)) {
            return $v;
        }

        $career = CareerPage::resolvedForPublic();

        return view('site.pages.career', [
            'title' => SiteDetail::pageTitle($career->hero_title),
            'metaDescription' => $career->meta_description,
            'career' => $career,
        ]);
    }

    public function aboutUs(): View
    {
        $aboutPage = AboutPage::singleton();
        $about = AboutPage::resolvedForPublic();
        $siteDetail = SiteDetail::query()->first();
        $pageTitle = filled($about->hero_title ?? null) ? $about->hero_title : 'About Us';

        return view('site.pages.about-us', [
            'title' => SiteDetail::pageTitle($pageTitle),
            'metaDescription' => $siteDetail?->metaDescriptionForSite(),
            'about' => $about,
            'siteDetails' => $siteDetail,
            'pageSections' => $aboutPage->pageSections()->ordered()->where('is_active', true)->get(),
        ]);
    }

    public function whereWeAre(Request $request): View
    {
        if ($v = $this->menuPageIfExists($request)) {
            return $v;
        }

        return view('site.pages.where-we-are', [
            'title' => SiteDetail::pageTitle('Where We Are'),
            'metaDescription' => 'Our service areas and locations across the region.',
        ]);
    }

    public function locations(Request $request): View
    {
        if ($v = $this->menuPageIfExists($request)) {
            return $v;
        }

        return view('site.pages.locations', [
            'title' => SiteDetail::pageTitle('Locations'),
            'metaDescription' => 'We serve all ports and straits of Turkey and the ARA area — 365 days, 24 hours delivery across West Europe.',
        ]);
    }

    public function award(Request $request): View
    {
        if ($v = $this->menuPageIfExists($request)) {
            return $v;
        }

        return view('site.pages.simple', [
            'title' => SiteDetail::pageTitle('Award'),
            'metaDescription' => 'Recognition and milestones from our partners and the industry.',
            'heading' => 'Award',
            'lead' => 'We are proud of the trust our clients place in us. Details and highlights will appear here as we update this section.',
        ]);
    }
}
