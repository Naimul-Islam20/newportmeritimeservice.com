<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use App\Models\HomeSection;
use App\Models\HomeServiceAreaSetting;
use App\Models\QualityCertificate;
use App\Models\SubMenu;
use App\Models\WhereWeAreLocation;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $homeSections = HomeSection::query()
            ->where('is_active', true)
            ->ordered()
            ->get();

        /** @var array<int, Collection<int, SubMenu>> $sectionItems */
        $sectionItems = [];

        foreach ($homeSections as $section) {
            if ($section->block_type !== 'carousel') {
                continue;
            }

            if (! $section->menu_id) {
                $sectionItems[$section->id] = collect();

                continue;
            }

            $q = SubMenu::query()
                ->where('menu_id', $section->menu_id)
                ->where('is_active', true);

            if ($section->variant === 'news') {
                $q->orderByRaw('published_at IS NULL')
                    ->orderByDesc('published_at');
            } else {
                $q->ordered();
            }

            $sectionItems[$section->id] = $q->get();
        }

        $hasCertificatesCarousel = $homeSections->contains(
            fn (HomeSection $section) => $section->block_type === 'logo_carousel',
        );

        $serviceArea = HomeServiceAreaSetting::displayPayload();
        $locationSlides = $this->buildWhereWeAreLocationSlides();
        if ($locationSlides !== []) {
            $serviceArea['branches']['items'] = $locationSlides;
        }

        return view('site.pages.home', [
            'heroSlides' => HeroSlide::query()->ordered()->get(),
            'homeSections' => $homeSections,
            'sectionItems' => $sectionItems,
            'serviceArea' => $serviceArea,
            'homeCertificates' => $hasCertificatesCarousel ? QualityCertificate::forHomeCarousel() : collect(),
        ]);
    }

    /**
     * @return list<array{image_url: string, url: string, label: string, subtitle: string|null}>
     */
    private function buildWhereWeAreLocationSlides(): array
    {
        $locations = WhereWeAreLocation::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $slides = [];
        foreach ($locations as $location) {
            $imageUrl = '';

            if (is_string($location->hero_background) && trim($location->hero_background) !== '') {
                $imageUrl = WhereWeAreLocation::imageSrc($location->hero_background);
            }

            if ($imageUrl === '') {
                $gallery = is_array($location->gallery_images) ? $location->gallery_images : [];
                foreach ($gallery as $imgPath) {
                    if (! is_string($imgPath) || trim($imgPath) === '') {
                        continue;
                    }
                    $imageUrl = WhereWeAreLocation::imageSrc($imgPath);
                    if ($imageUrl !== '') {
                        break;
                    }
                }
            }

            if ($imageUrl === '') {
                continue;
            }

            $slides[] = [
                'image_url' => $imageUrl,
                'url' => route('where-we-are.location', $location->slug),
                'label' => trim((string) ($location->hero_title ?: $location->sidebar_label)),
                'subtitle' => filled($location->office_title) ? $location->office_title : $location->region_label,
            ];
        }

        return $slides;
    }
}
