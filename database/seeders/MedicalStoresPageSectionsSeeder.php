<?php

namespace Database\Seeders;

use App\Models\MenuPageSection;
use App\Models\SubMenu;
use Illuminate\Database\Seeder;

/**
 * Seeds two page sections on the Medical Stores submenu (same pattern as Heavy Equipment).
 *
 * Run: php artisan db:seed --class=MedicalStoresPageSectionsSeeder
 *
 * Section 01: Text & input — title + two paragraphs (description + bottom description).
 * Section 02: Image & details — title + bullet points + placeholder image.
 */
class MedicalStoresPageSectionsSeeder extends Seeder
{
    private const SECTION_1_TITLE = 'Medical Stores Supply';

    private const SECTION_2_TITLE = 'Medical Stores Range';

    /** Relative to public/ (public_site disk). */
    private const PLACEHOLDER_IMAGE_PATH = 'page-sections/images/kNZhbogYv7yrqnZzfz9C4EtJD8r32L8ORZJCavN3.jpg';

    private const DESCRIPTION_1 = <<<'TXT'
We provide a complete range of marine medical store supplies to ensure the health, safety, and well-being of crew members onboard. Our products are carefully sourced to meet international maritime medical standards, ensuring reliability and compliance for all types of vessels.
TXT;

    private const DESCRIPTION_2 = <<<'TXT'
With a strong supply network and timely delivery service, we support ships with essential medical items required for emergency care, routine treatment, and onboard health management.
TXT;

    /** @var list<string> */
    private const POINTS = [
        'First aid kits & medical boxes',
        'Essential medicines & tablets',
        'Pain relief & fever medicines',
        'Antibiotics & antiseptics',
        'Bandages & wound care items',
        'Medical gloves & PPE items',
        'Surgical tools & instruments',
        'Thermometers & basic diagnostic tools',
        'Sea sickness & motion sickness medicines',
        'Emergency medical supplies',
    ];

    public function run(): void
    {
        $sub = SubMenu::query()
            ->where(function ($q): void {
                $q->whereRaw('lower(trim(label)) = ?', ['medical stores'])
                    ->orWhereRaw('lower(trim(url)) like ?', ['%medical-store%']);
            })
            ->orderBy('id')
            ->first();

        if ($sub === null) {
            $this->command?->warn('No SubMenu found with label "Medical Stores" or URL containing "medical-store". Skipping.');

            return;
        }

        $this->syncSection(
            $sub,
            'text_input',
            self::SECTION_1_TITLE,
            [
                'mini_title' => null,
                'description' => trim(self::DESCRIPTION_1),
                'bottom_description' => trim(self::DESCRIPTION_2),
                'points' => null,
                'image_path' => null,
            ]
        );

        $this->syncSection(
            $sub,
            'two_column_image_details',
            self::SECTION_2_TITLE,
            [
                'layout_width' => 'short',
                'mini_title' => null,
                'description' => null,
                'image_path' => self::PLACEHOLDER_IMAGE_PATH,
                'image_side' => 'left',
                'points' => self::POINTS,
            ]
        );

        $this->command?->info('Synced Medical Stores sections on SubMenu id '.$sub->id.' ('.$sub->label.').');
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function syncSection(SubMenu $sub, string $type, string $title, array $data): void
    {
        $existing = MenuPageSection::query()
            ->where('sectionable_type', SubMenu::class)
            ->where('sectionable_id', $sub->id)
            ->where('type', $type)
            ->where('title', $title)
            ->first();

        if ($existing !== null) {
            $existing->update([
                'data' => $data,
                'is_active' => true,
            ]);

            return;
        }

        $nextOrder = (int) (MenuPageSection::query()
            ->where('sectionable_type', SubMenu::class)
            ->where('sectionable_id', $sub->id)
            ->max('sort_order') ?? 0) + 1;

        MenuPageSection::query()->create([
            'sectionable_type' => SubMenu::class,
            'sectionable_id' => $sub->id,
            'type' => $type,
            'title' => $title,
            'data' => $data,
            'sort_order' => $nextOrder,
            'is_active' => true,
        ]);
    }
}
