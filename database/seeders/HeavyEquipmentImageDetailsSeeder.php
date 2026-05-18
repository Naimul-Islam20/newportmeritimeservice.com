<?php

namespace Database\Seeders;

use App\Models\MenuPageSection;
use App\Models\SubMenu;
use Illuminate\Database\Seeder;

/**
 * Seeds one "Image & details" section on the Heavy Equipment submenu.
 *
 * Run: php artisan db:seed --class=HeavyEquipmentImageDetailsSeeder
 *
 * Requires a SubMenu whose label is "Heavy Equipment" (case-insensitive) or whose URL contains "heavy-equipment".
 * Placeholder image path exists in repo under public/page-sections/images/ — replace via admin when ready.
 */
class HeavyEquipmentImageDetailsSeeder extends Seeder
{
    private const SECTION_TITLE = 'Our Heavy Equipment Includes';

    /** @var list<string> */
    private const POINTS = [
        'Cranes & lifting equipment',
        'Winches & hoisting systems',
        'Hydraulic machinery',
        'Air compressors',
        'Generators & power units',
        'Pumps & pumping systems',
        'Forklifts & handling equipment',
        'Welding machines & tools',
        'Marine workshop machinery',
        'Deck machinery systems',
    ];

    /** Relative to public/ (public_site disk). */
    private const PLACEHOLDER_IMAGE_PATH = 'page-sections/images/RmctbdCZXpMwdkmqoXNnsZK4PlHJyyuCZZ0dkFDy.jpg';

    public function run(): void
    {
        $sub = SubMenu::query()
            ->where(function ($q): void {
                $q->whereRaw('lower(trim(label)) = ?', ['heavy equipment'])
                    ->orWhereRaw('lower(trim(url)) like ?', ['%heavy-equipment%']);
            })
            ->orderBy('id')
            ->first();

        if ($sub === null) {
            $this->command?->warn('No SubMenu found with label "Heavy Equipment" or URL containing "heavy-equipment". Skipping.');

            return;
        }

        /** @var array<string, mixed> $data */
        $data = [
            'layout_width' => 'short',
            'mini_title' => null,
            'description' => null,
            'image_path' => self::PLACEHOLDER_IMAGE_PATH,
            'image_side' => 'left',
            'points' => self::POINTS,
        ];

        $existing = MenuPageSection::query()
            ->where('sectionable_type', SubMenu::class)
            ->where('sectionable_id', $sub->id)
            ->where('type', 'two_column_image_details')
            ->where('title', self::SECTION_TITLE)
            ->first();

        if ($existing !== null) {
            $existing->update([
                'data' => $data,
                'is_active' => true,
            ]);
            $this->command?->info('Updated Image & details section on SubMenu id '.$sub->id.' ('.$sub->label.').');

            return;
        }

        $nextOrder = (int) (MenuPageSection::query()
            ->where('sectionable_type', SubMenu::class)
            ->where('sectionable_id', $sub->id)
            ->max('sort_order') ?? 0) + 1;

        MenuPageSection::query()->create([
            'sectionable_type' => SubMenu::class,
            'sectionable_id' => $sub->id,
            'type' => 'two_column_image_details',
            'title' => self::SECTION_TITLE,
            'data' => $data,
            'sort_order' => $nextOrder,
            'is_active' => true,
        ]);

        $this->command?->info('Created Image & details section on SubMenu id '.$sub->id.' ('.$sub->label.').');
    }
}
