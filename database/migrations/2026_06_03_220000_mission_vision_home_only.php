<?php

use App\Models\AboutPage;
use App\Models\HomeSection;
use App\Models\MenuPageSection;
use App\Models\SubMenu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $sectionData = $this->missionVisionSectionData();

        $about = AboutPage::query()->first();
        if ($about && $sectionData !== null) {
            $parsed = $this->parseMissionVisionFromSectionData($sectionData);
            $updates = [];
            if (! filled($about->mission_body) && filled($parsed['mission_body'])) {
                $updates['mission_body'] = trim((string) $parsed['mission_body']);
            }
            if (! filled($about->vision_body) && filled($parsed['vision_body'])) {
                $updates['vision_body'] = trim((string) $parsed['vision_body']);
            }
            if (! filled($about->mission_title) && filled($parsed['mission_title'])) {
                $updates['mission_title'] = trim((string) $parsed['mission_title']);
            }
            if (! filled($about->vision_title) && filled($parsed['vision_title'])) {
                $updates['vision_title'] = trim((string) $parsed['vision_title']);
            }
            if ($updates !== []) {
                $about->update($updates);
            }
        }

        HomeSection::query()
            ->where('block_type', 'two_column')
            ->where('two_column_mode', 'split_cta')
            ->each(function (HomeSection $section): void {
                $section->update([
                    'variant' => 'mission_vision',
                    'title' => null,
                    'description' => null,
                    'button_label' => null,
                    'button_url' => null,
                    'data' => array_diff_key(
                        is_array($section->data) ? $section->data : [],
                        array_flip([
                            'secondary_description',
                            'secondary_button_label',
                            'secondary_button_url',
                        ])
                    ),
                ]);
            });

        HomeSection::query()
            ->where('block_type', 'two_column')
            ->where('two_column_mode', 'both_sides_details')
            ->where('variant', 'mission_vision')
            ->update(['is_active' => false]);

        SubMenu::query()
            ->where(function ($q): void {
                $q->where('url', '/our-values-mission-vision')
                    ->orWhere('url', 'our-values-mission-vision');
            })
            ->update(['is_active' => false]);

        MenuPageSection::query()
            ->where('type', 'two_column_two_side_details')
            ->whereHasMorph('sectionable', [SubMenu::class], function ($q): void {
                $q->where('url', '/our-values-mission-vision')
                    ->orWhere('url', 'our-values-mission-vision');
            })
            ->update(['is_active' => false]);

        MenuPageSection::query()
            ->where('type', 'two_column_two_side_details')
            ->whereHasMorph('sectionable', [AboutPage::class])
            ->update(['is_active' => false]);
    }

    public function down(): void
    {
        SubMenu::query()
            ->where(function ($q): void {
                $q->where('url', '/our-values-mission-vision')
                    ->orWhere('url', 'our-values-mission-vision');
            })
            ->update(['is_active' => true]);

        MenuPageSection::query()
            ->where('type', 'two_column_two_side_details')
            ->whereHasMorph('sectionable', [SubMenu::class], function ($q): void {
                $q->where('url', '/our-values-mission-vision')
                    ->orWhere('url', 'our-values-mission-vision');
            })
            ->update(['is_active' => true]);

        MenuPageSection::query()
            ->where('type', 'two_column_two_side_details')
            ->whereHasMorph('sectionable', [AboutPage::class])
            ->update(['is_active' => true]);
    }

    /** @return array<string, mixed>|null */
    private function missionVisionSectionData(): ?array
    {
        $sub = SubMenu::query()
            ->where(function ($q): void {
                $q->where('url', '/our-values-mission-vision')
                    ->orWhere('url', 'our-values-mission-vision');
            })
            ->first();

        if ($sub) {
            $section = $sub->pageSections()
                ->where('type', 'two_column_two_side_details')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->first();

            if ($section && is_array($section->data)) {
                return $section->data;
            }
        }

        $aboutPage = AboutPage::query()->first();
        if ($aboutPage) {
            $section = $aboutPage->pageSections()
                ->where('type', 'two_column_two_side_details')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->first();

            if ($section && is_array($section->data)) {
                return $section->data;
            }
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{mission_title: mixed, mission_body: mixed, vision_title: mixed, vision_body: mixed}
     */
    private function parseMissionVisionFromSectionData(array $data): array
    {
        $leftTitle = strtolower(trim((string) data_get($data, 'left_title', '')));
        $rightTitle = strtolower(trim((string) data_get($data, 'right_title', '')));
        $leftDesc = data_get($data, 'left_description');
        $rightDesc = data_get($data, 'right_description');
        $leftIsVision = str_contains($leftTitle, 'vision');
        $rightIsVision = str_contains($rightTitle, 'vision');

        if ($leftIsVision && ! $rightIsVision) {
            return [
                'mission_title' => data_get($data, 'right_title'),
                'mission_body' => $rightDesc,
                'vision_title' => data_get($data, 'left_title'),
                'vision_body' => $leftDesc,
            ];
        }

        return [
            'mission_title' => data_get($data, 'left_title'),
            'mission_body' => $leftDesc,
            'vision_title' => data_get($data, 'right_title'),
            'vision_body' => $rightDesc,
        ];
    }
};
