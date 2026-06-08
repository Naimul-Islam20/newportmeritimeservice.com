<?php

namespace App\Models;

use App\Support\PublicUploadUrl;
use Illuminate\Database\Eloquent\Model;

class HomeServiceAreaSetting extends Model
{
    protected $table = 'home_service_area_settings';

    protected $fillable = [
        'mini_title',
        'title',
        'map_image_path',
        'highlight_title',
        'highlight_description',
        'steps',
        'branches_mini_title',
        'branches_title',
        'branches_view_all_label',
        'branches_view_all_url',
        'branches_items',
    ];

    protected function casts(): array
    {
        return [
            'steps' => 'array',
            'branches_items' => 'array',
        ];
    }

    /**
     * @return array{
     *     mini_title: string,
     *     title: string,
     *     map_image_path: null,
     *     highlight_title: string,
     *     highlight_description: string,
     *     steps: list<string>,
     *     branches_mini_title: string,
     *     branches_title: string,
     *     branches_view_all_label: string,
     *     branches_view_all_url: string,
     *     branches_items: list<array{path: null, url: null, label: null}>
     * }
     */
    public static function defaultAttributes(): array
    {
        return [
            'mini_title' => 'Service Areas',
            'title' => 'Locations',
            'map_image_path' => null,
            'highlight_title' => 'End to end supply',
            'highlight_description' => 'We pride ourselves on our delivery and operate 365 days, 24 hours non-stop in all of the ports and straits of Turkey and the ARA area.',
            'steps' => [
                'Getting Order',
                "Preparing order and\npackaging process",
                "Safe delivery service in\nthe refrigerated trucks",
                'On-time delivery',
            ],
            'branches_mini_title' => 'Where We Are',
            'branches_title' => 'Branch Offices & Warehouses',
            'branches_view_all_label' => 'View all',
            'branches_view_all_url' => '/contact',
            'branches_items' => [],
        ];
    }

    public const BRANCH_PORT_SLOT_COUNT = 4;

    /**
     * @return list<array{path: string, label: string, subtitle: string, url: string}>
     */
    public static function defaultBranchItemsStored(): array
    {
        return [
            [
                'path' => 'https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?auto=format&fit=crop&w=900&h=520&q=80',
                'label' => 'Chattogram Port',
                'subtitle' => 'Chattogram Port Authority',
                'url' => '/contact',
            ],
            [
                'path' => 'https://images.unsplash.com/photo-1578575437136-9c13f6c966fc?auto=format&fit=crop&w=900&h=520&q=80',
                'label' => 'Mongla Port',
                'subtitle' => 'Mongla Port Authority',
                'url' => '/contact',
            ],
            [
                'path' => 'https://images.unsplash.com/photo-1494412574743-01927c452424?auto=format&fit=crop&w=900&h=520&q=80',
                'label' => 'Payra Port',
                'subtitle' => 'Payra Port Authority',
                'url' => '/contact',
            ],
            [
                'path' => 'https://images.unsplash.com/photo-1565008576549-57569a49371d?auto=format&fit=crop&w=900&h=520&q=80',
                'label' => "Cox's Bazar Port",
                'subtitle' => 'Fishing Harbour',
                'url' => '/contact',
            ],
        ];
    }

    /**
     * @param  list<array{path?: string|null, url?: string|null, label?: string|null, subtitle?: string|null}>|null  $stored
     * @return list<array{path: string, label: string, subtitle: string, url: string}>
     */
    public static function branchItemsForAdmin(?array $stored): array
    {
        $defaults = self::defaultBranchItemsStored();
        $stored = is_array($stored) ? array_values($stored) : [];
        $out = [];

        for ($i = 0; $i < self::BRANCH_PORT_SLOT_COUNT; $i++) {
            $def = $defaults[$i];
            $item = is_array($stored[$i] ?? null) ? $stored[$i] : [];
            $out[] = [
                'path' => filled($item['path'] ?? null) ? trim((string) $item['path']) : $def['path'],
                'label' => filled($item['label'] ?? null) ? trim((string) $item['label']) : $def['label'],
                'subtitle' => filled($item['subtitle'] ?? null) ? trim((string) $item['subtitle']) : $def['subtitle'],
                'url' => filled($item['url'] ?? null) ? trim((string) $item['url']) : $def['url'],
            ];
        }

        return $out;
    }

    /**
     * @param  list<array{path?: string|null, url?: string|null, label?: string|null, subtitle?: string|null}>|null  $raw
     * @return list<array{image_url: string, url: string|null, label: string, subtitle: string|null}>
     */
    public static function normalizeBranchCarouselItems(?array $raw): array
    {
        $defaults = self::defaultBranchItemsStored();
        $stored = is_array($raw) ? array_values($raw) : [];
        $out = [];

        for ($i = 0; $i < self::BRANCH_PORT_SLOT_COUNT; $i++) {
            $def = $defaults[$i];
            $item = is_array($stored[$i] ?? null) ? $stored[$i] : [];

            $path = is_string($item['path'] ?? null) ? trim($item['path']) : '';
            if ($path === '') {
                $path = $def['path'];
            }

            $imageUrl = str_starts_with($path, 'http://') || str_starts_with($path, 'https://')
                ? $path
                : PublicUploadUrl::fromPath($path);

            if ($imageUrl === '') {
                $imageUrl = $def['path'];
            }

            $label = is_string($item['label'] ?? null) ? trim($item['label']) : '';
            $subtitle = is_string($item['subtitle'] ?? null) ? trim($item['subtitle']) : '';
            $url = is_string($item['url'] ?? null) ? trim($item['url']) : '';

            $out[] = [
                'image_url' => $imageUrl,
                'url' => $url !== '' ? $url : $def['url'],
                'label' => $label !== '' ? $label : $def['label'],
                'subtitle' => $subtitle !== '' ? $subtitle : $def['subtitle'],
            ];
        }

        return $out;
    }

    /**
     * Payload for the home page section (falls back to defaults when no row exists).
     *
     * @return array{
     *     mini_title: string,
     *     title: string,
     *     map_image_path: string|null,
     *     highlight_title: string,
     *     highlight_description: string,
     *     steps: list<string>,
     *     branches: array{
     *         mini_title: string,
     *         title: string,
     *         view_all_label: string,
     *         view_all_url: string,
     *         items: list<array{image_url: string, url: string|null, label: string}>
     *     }
     * }
     */
    public static function displayPayload(): array
    {
        $defaults = self::defaultAttributes();
        $row = self::query()->first();

        if (! $row) {
            return [
                ...$defaults,
                'map_image_path' => null,
                'branches' => self::branchesPayloadFrom($defaults, null),
            ];
        }

        $steps = $defaults['steps'];
        if (is_array($row->steps)) {
            $steps = array_values(array_filter(
                array_map(fn ($s) => is_string($s) ? trim($s) : '', $row->steps),
                fn ($s) => $s !== ''
            ));
        }

        $mapPath = is_string($row->map_image_path) ? trim($row->map_image_path) : '';
        $mapPath = $mapPath !== '' ? $mapPath : null;

        $attrs = [
            'mini_title' => $row->mini_title ?? $defaults['mini_title'],
            'title' => $row->title ?? $defaults['title'],
            'map_image_path' => $mapPath,
            'highlight_title' => $row->highlight_title ?? $defaults['highlight_title'],
            'highlight_description' => $row->highlight_description ?? $defaults['highlight_description'],
            'steps' => $steps,
            'branches_mini_title' => $row->branches_mini_title ?? $defaults['branches_mini_title'],
            'branches_title' => $row->branches_title ?? $defaults['branches_title'],
            'branches_view_all_label' => $row->branches_view_all_label ?? $defaults['branches_view_all_label'],
            'branches_view_all_url' => $row->branches_view_all_url ?? $defaults['branches_view_all_url'],
        ];

        return [
            ...$attrs,
            'branches' => self::branchesPayloadFrom($attrs, is_array($row->branches_items) ? $row->branches_items : null),
        ];
    }

    /**
     * @param  array<string, mixed>  $attrs
     * @param  list<array{path?: string|null, url?: string|null, label?: string|null}>|null  $storedItems
     * @return array{
     *     mini_title: string,
     *     title: string,
     *     view_all_label: string,
     *     view_all_url: string,
     *     items: list<array{image_url: string, url: string|null, label: string}>
     * }
     */
    private static function branchesPayloadFrom(array $attrs, ?array $storedItems): array
    {
        $viewAllUrl = is_string($attrs['branches_view_all_url'] ?? null) ? trim($attrs['branches_view_all_url']) : '';
        if ($viewAllUrl !== '' && ! str_starts_with($viewAllUrl, 'http') && ! str_starts_with($viewAllUrl, '/')) {
            $viewAllUrl = '/'.ltrim($viewAllUrl, '/');
        }

        $branchesMini = trim((string) ($attrs['branches_mini_title'] ?? ''));
        $branchesTitle = trim((string) ($attrs['branches_title'] ?? ''));
        $viewAllLabel = trim((string) ($attrs['branches_view_all_label'] ?? ''));

        return [
            'mini_title' => $branchesMini !== '' ? $branchesMini : 'Where We Are',
            'title' => $branchesTitle !== '' ? $branchesTitle : 'Branch Offices & Warehouses',
            'view_all_label' => $viewAllLabel !== '' ? $viewAllLabel : 'View all',
            'view_all_url' => $viewAllUrl !== '' ? $viewAllUrl : '/contact',
            'items' => self::resolveBranchCarouselItems($storedItems),
        ];
    }

    /**
     * @param  list<array{path?: string|null, url?: string|null, label?: string|null, subtitle?: string|null}>|null  $storedItems
     * @return list<array{image_url: string, url: string|null, label: string, subtitle: string|null}>
     */
    private static function resolveBranchCarouselItems(?array $storedItems): array
    {
        $fromLocations = WhereWeAreLocation::homeBranchCarouselItems();

        if ($fromLocations !== []) {
            return $fromLocations;
        }

        return self::normalizeBranchCarouselItems($storedItems);
    }
}
