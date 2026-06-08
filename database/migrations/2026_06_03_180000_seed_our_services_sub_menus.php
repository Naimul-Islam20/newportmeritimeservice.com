<?php

use App\Models\Menu;
use App\Models\SubMenu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * @var list<array{label: string, url: string, description: string|null, sort_order: int}>
     */
    private array $ourServicesPages = [
        [
            'label' => 'Ship Repair & Maintenance',
            'url' => '/our-services/ship-repair-maintenance',
            'description' => 'Crane, Grab, Shell Plate, hull structure, Engine, Ac, compressors, Motor, etc.',
            'sort_order' => 0,
        ],
        [
            'label' => 'LSA & FFA Annual Inspection',
            'url' => '/our-services/lsa-ffa-annual-inspection',
            'description' => null,
            'sort_order' => 1,
        ],
        [
            'label' => 'Life Raft Maintenance',
            'url' => '/our-services/life-raft-maintenance',
            'description' => null,
            'sort_order' => 2,
        ],
        [
            'label' => 'Bunkering Service',
            'url' => '/our-services/bunkering-service',
            'description' => 'Reliable delivery of LSMGO (0.1%), VLSFO (0.5%), and HSFO via our owned barges. De-bunkering: Safe offloading of off-spec or contaminated fuel, adhering to environmental and safety regulations. Lubricant Supply: Quality marine lubricants and greases for engines and machinery, delivered in bulk or drums. Also we do De-bunkering, De-Slopping.',
            'sort_order' => 3,
        ],
        [
            'label' => 'Waste Disposal',
            'url' => '/our-services/waste-disposal',
            'description' => 'Sludge & Garbage Discharge, De-bunkering, De-Slopping.',
            'sort_order' => 4,
        ],
        [
            'label' => 'Cargo Hold Cleaning',
            'url' => '/our-services/cargo-hold-cleaning',
            'description' => 'Cargo Hatch, Tank & Engine Room Cleaning.',
            'sort_order' => 5,
        ],
        [
            'label' => 'Chipping, Painting & Surface Treatment',
            'url' => '/our-services/chipping-painting-surface-treatment',
            'description' => null,
            'sort_order' => 6,
        ],
        [
            'label' => 'Underwater Hull & Propeller Cleaning',
            'url' => '/our-services/underwater-hull-propeller-cleaning',
            'description' => 'Expert Hull Cleaning, Propeller Cleaning & Polishing, Underwater Debris Removal, Underwater Inspections.',
            'sort_order' => 7,
        ],
        [
            'label' => 'Agency Service',
            'url' => '/our-services/agency-service',
            'description' => 'Ship spare in transit, Shipment custom clearance & onboard delivery, Crew Changes, Shore grab rental — Both Auto & Manual, Fender Service, Other husbandry needs of the Ship Owners.',
            'sort_order' => 8,
        ],
        [
            'label' => 'Other Service',
            'url' => '/our-services/other-service',
            'description' => 'Import-Export, Logistics Services, Trading.',
            'sort_order' => 9,
        ],
    ];

    public function up(): void
    {
        $ourServices = $this->resolveOurServicesMenu();

        if (! $ourServices) {
            return;
        }

        $ourServices->update([
            'label' => 'Our Services',
            'url' => '/our-services',
            'sort_order' => 20,
            'is_active' => true,
        ]);

        foreach ($this->ourServicesPages as $page) {
            $existing = SubMenu::query()
                ->where('menu_id', $ourServices->id)
                ->whereNull('parent_sub_menu_id')
                ->where(function ($q) use ($page): void {
                    $q->where('url', $page['url'])
                        ->orWhere('url', ltrim($page['url'], '/'));
                })
                ->first();

            $payload = [
                'label' => $page['label'],
                'url' => $page['url'],
                'description' => $page['description'],
                'sort_order' => $page['sort_order'],
                'is_active' => true,
            ];

            if ($existing) {
                $existing->update($payload);

                continue;
            }

            SubMenu::query()->create(array_merge($payload, [
                'menu_id' => $ourServices->id,
            ]));
        }

        $activeUrls = collect($this->ourServicesPages)
            ->pluck('url')
            ->flatMap(fn (string $url) => [$url, ltrim($url, '/')])
            ->unique()
            ->all();

        SubMenu::query()
            ->where('menu_id', $ourServices->id)
            ->whereNull('parent_sub_menu_id')
            ->whereNotIn('url', $activeUrls)
            ->update(['is_active' => false]);
    }

    public function down(): void
    {
        $ourServices = $this->resolveOurServicesMenu();

        if (! $ourServices) {
            return;
        }

        $activeUrls = collect($this->ourServicesPages)
            ->pluck('url')
            ->flatMap(fn (string $url) => [$url, ltrim($url, '/')])
            ->unique()
            ->all();

        SubMenu::query()
            ->where('menu_id', $ourServices->id)
            ->whereNull('parent_sub_menu_id')
            ->whereIn('url', $activeUrls)
            ->update(['is_active' => false]);
    }

    private function resolveOurServicesMenu(): ?Menu
    {
        return Menu::query()
            ->where(function ($q): void {
                $q->where('url', '/our-services')
                    ->orWhere('url', 'our-services')
                    ->orWhereRaw('LOWER(label) LIKE ?', ['%our services%']);
            })
            ->orderBy('id')
            ->first();
    }
};
