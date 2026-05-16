<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use App\Models\HomeSection;
use App\Models\HomeServiceAreaSetting;
use App\Models\HomeVisualFramesSetting;
use App\Models\SubMenu;
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

        return view('site.pages.home', [
            'heroSlides' => HeroSlide::query()->ordered()->get(),
            'homeSections' => $homeSections,
            'sectionItems' => $sectionItems,
            'serviceArea' => HomeServiceAreaSetting::displayPayload(),
            'visualFrames' => HomeVisualFramesSetting::displayPayload(),
        ]);
    }
}
