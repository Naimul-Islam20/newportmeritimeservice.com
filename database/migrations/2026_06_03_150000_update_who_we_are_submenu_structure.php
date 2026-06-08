<?php

use App\Models\AboutPage;
use App\Models\HomeSection;
use App\Models\Menu;
use App\Models\MenuPageSection;
use App\Models\SubMenu;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * @var list<array{label: string, url: string, sort_order: int}>
     */
    private array $pages = [
        ['label' => 'About Us', 'url' => '/about-us', 'sort_order' => 0],
        ['label' => 'Where we are', 'url' => '/where-we-are', 'sort_order' => 1],
        ['label' => 'Our Values - Mission & Vision', 'url' => '/our-values-mission-vision', 'sort_order' => 2],
        ['label' => 'Our commitment', 'url' => '/our-commitment', 'sort_order' => 3],
        ['label' => 'Why Choose NewPort?', 'url' => '/why-choose-newport', 'sort_order' => 4],
    ];

    /**
     * @var list<string>
     */
    private array $retiredUrls = [
        '/our-story',
        '/message-from-ceo',
        '/our-team-management',
        '/contact',
    ];

    public function up(): void
    {
        $parent = $this->resolveWhoWeAreMenu();

        if (! $parent) {
            return;
        }

        if (Str::lower(trim($parent->label)) === 'home') {
            $parent->update(['label' => 'WHO WE ARE']);
        }

        foreach ($this->pages as $page) {
            $this->upsertTopLevelSubMenu($parent, $page);
        }

        $activeUrls = collect($this->pages)
            ->pluck('url')
            ->flatMap(fn (string $url) => [$url, ltrim($url, '/')])
            ->unique()
            ->all();

        SubMenu::query()
            ->where('menu_id', $parent->id)
            ->whereNull('parent_sub_menu_id')
            ->whereNotIn('url', $activeUrls)
            ->update(['is_active' => false]);

        $this->seedContentPages($parent);
    }

    public function down(): void
    {
        $parent = $this->resolveWhoWeAreMenu();

        if (! $parent) {
            return;
        }

        $newUrls = collect($this->pages)->pluck('url')->flatMap(fn (string $url) => [$url, ltrim($url, '/')])->unique();

        SubMenu::query()
            ->where('menu_id', $parent->id)
            ->whereNull('parent_sub_menu_id')
            ->whereIn('url', $newUrls->all())
            ->whereNotIn('url', ['/about-us', '/where-we-are', 'about-us', 'where-we-are'])
            ->delete();

        foreach ($this->retiredUrls as $url) {
            SubMenu::query()
                ->where('menu_id', $parent->id)
                ->whereNull('parent_sub_menu_id')
                ->where(function ($q) use ($url): void {
                    $q->where('url', $url)->orWhere('url', ltrim($url, '/'));
                })
                ->update(['is_active' => true]);
        }
    }

    /**
     * @param  array{label: string, url: string, sort_order: int}  $page
     */
    private function upsertTopLevelSubMenu(Menu $parent, array $page): SubMenu
    {
        $existing = SubMenu::query()
            ->where('menu_id', $parent->id)
            ->whereNull('parent_sub_menu_id')
            ->where(function ($q) use ($page): void {
                $q->where('url', $page['url'])
                    ->orWhere('url', ltrim($page['url'], '/'));
            })
            ->first();

        if ($existing) {
            $existing->update([
                'label' => $page['label'],
                'sort_order' => $page['sort_order'],
                'is_active' => true,
            ]);

            return $existing;
        }

        return SubMenu::query()->create([
            'menu_id' => $parent->id,
            'label' => $page['label'],
            'url' => $page['url'],
            'sort_order' => $page['sort_order'],
            'is_active' => true,
        ]);
    }

    private function seedContentPages(Menu $parent): void
    {
        $about = AboutPage::query()->first();
        $aboutPage = AboutPage::singleton();

        $missionVision = $this->findSubMenu($parent, '/our-values-mission-vision');
        if ($missionVision && ! $missionVision->pageSections()->exists()) {
            $leftTitle = filled($about?->mission_title) ? $about->mission_title : 'Our Mission';
            $rightTitle = filled($about?->vision_title) ? $about->vision_title : 'Our Vision';
            $leftDesc = filled($about?->mission_body) ? $about->mission_body : null;
            $rightDesc = filled($about?->vision_body) ? $about->vision_body : null;

            if ($leftDesc === null && $rightDesc === null) {
                $legacy = $aboutPage->pageSections()
                    ->where('type', 'two_column_two_side_details')
                    ->ordered()
                    ->first();

                if ($legacy) {
                    $data = is_array($legacy->data) ? $legacy->data : [];
                    $leftTitle = data_get($data, 'left_title', $leftTitle);
                    $rightTitle = data_get($data, 'right_title', $rightTitle);
                    $leftDesc = data_get($data, 'left_description');
                    $rightDesc = data_get($data, 'right_description');
                }
            }

            if ($leftDesc !== null || $rightDesc !== null) {
                $missionVision->pageSections()->create([
                    'type' => 'two_column_two_side_details',
                    'title' => 'Our Values - Mission & Vision',
                    'data' => [
                        'left_title' => $leftTitle,
                        'left_description' => $leftDesc,
                        'right_title' => $rightTitle,
                        'right_description' => $rightDesc,
                    ],
                    'sort_order' => 0,
                    'is_active' => true,
                ]);
            }
        }

        $commitment = $this->findSubMenu($parent, '/our-commitment');
        if ($commitment && ! $commitment->pageSections()->exists()) {
            $aboutSection = $aboutPage->pageSections()
                ->where('type', 'text_input')
                ->ordered()
                ->first();

            $description = null;
            if ($aboutSection) {
                $description = data_get($aboutSection->data, 'description');
            }

            if (! filled($description)) {
                $description = 'We are committed to honest service, quality supply, and dependable support for every vessel we serve — delivering on time, every time, with transparency and care.';
            }

            $commitment->pageSections()->create([
                'type' => 'text_input',
                'title' => 'Our commitment',
                'data' => [
                    'description' => $description,
                ],
                'sort_order' => 0,
                'is_active' => true,
            ]);
        }

        $whyChoose = $this->findSubMenu($parent, '/why-choose-newport');
        if ($whyChoose && ! $whyChoose->pageSections()->exists()) {
            $legacyWhy = $aboutPage->pageSections()
                ->where('type', 'two_column_image_details')
                ->ordered()
                ->get()
                ->first(function (MenuPageSection $section): bool {
                    $title = Str::lower((string) ($section->title ?? ''));

                    return Str::contains($title, 'why');
                });

            if ($legacyWhy) {
                $whyChoose->pageSections()->create([
                    'type' => $legacyWhy->type,
                    'title' => 'Why Choose NewPort?',
                    'data' => $legacyWhy->data,
                    'sort_order' => 0,
                    'is_active' => true,
                ]);
            } else {
                $homeWhy = HomeSection::query()
                    ->where('block_type', 'two_column')
                    ->where('variant', 'about')
                    ->whereRaw('LOWER(title) LIKE ?', ['%why choose%'])
                    ->first();

                $whyChoose->pageSections()->create([
                    'type' => 'two_column_image_details',
                    'title' => 'Why Choose NewPort?',
                    'data' => [
                        'mini_title' => $homeWhy?->mini_title,
                        'description' => $homeWhy?->description,
                        'points' => $homeWhy?->points,
                        'image_path' => $homeWhy?->image_path,
                        'image_side' => data_get($homeWhy?->data, 'image_side', 'right'),
                        'layout_width' => $homeWhy?->layout_width ?: 'short',
                    ],
                    'sort_order' => 0,
                    'is_active' => true,
                ]);
            }
        }
    }

    private function findSubMenu(Menu $parent, string $url): ?SubMenu
    {
        return SubMenu::query()
            ->where('menu_id', $parent->id)
            ->whereNull('parent_sub_menu_id')
            ->where(function ($q) use ($url): void {
                $q->where('url', $url)->orWhere('url', ltrim($url, '/'));
            })
            ->first();
    }

    private function resolveWhoWeAreMenu(): ?Menu
    {
        $whoWeAre = Menu::query()
            ->whereRaw('LOWER(label) LIKE ?', ['%who we are%'])
            ->orderBy('sort_order')
            ->first();

        if ($whoWeAre) {
            return $whoWeAre;
        }

        $homeWithChildren = Menu::query()
            ->where('url', '/')
            ->whereHas('subMenus')
            ->orderBy('sort_order')
            ->first();

        if ($homeWithChildren) {
            return $homeWithChildren;
        }

        return Menu::query()->where('url', '/')->orderBy('sort_order')->first();
    }
};
