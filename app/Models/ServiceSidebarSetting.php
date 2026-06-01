<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceSidebarSetting extends Model
{
    protected $fillable = [
        'categories_title',
        'nav_groups',
        'nav_links',
        'spare_parts_title',
        'spare_parts_text',
        'spare_parts_button_label',
        'brochures_title',
        'brochures_text',
        'brochure_label',
        'brochure_url',
        'quote_title',
    ];

    protected function casts(): array
    {
        return [
            'nav_groups' => 'array',
            'nav_links' => 'array',
        ];
    }

    public static function singleton(): self
    {
        return self::query()->firstOrCreate([], self::defaultContent());
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaultContent(): array
    {
        return [
            'categories_title' => 'Our Service Categories',
            'nav_groups' => [
                [
                    'id' => 'technical-stores',
                    'label' => 'Technical Stores',
                    'children' => [
                        ['label' => 'Engine Stores', 'href' => '/ship-supply'],
                        ['label' => 'Valves', 'href' => '#'],
                        ['label' => 'Deck Stores', 'href' => '#'],
                        ['label' => 'Safety Equipments', 'href' => '#'],
                        ['label' => 'Cabin Stores', 'href' => '#'],
                        ['label' => 'Nautical & Stationery Items', 'href' => '#'],
                        ['label' => 'Galley Stores', 'href' => '#'],
                    ],
                ],
                [
                    'id' => 'provision',
                    'label' => 'Provision',
                    'children' => [
                        ['label' => 'Dry Foods', 'href' => '#'],
                        ['label' => 'Frozen Foods', 'href' => '#'],
                        ['label' => 'Fresh Fruits & Vegetables', 'href' => '#'],
                        ['label' => 'Beverages', 'href' => '#'],
                    ],
                ],
            ],
            'nav_links' => [
                ['label' => 'Transit Delivery', 'href' => '/our-services/transit-delivery'],
                ['label' => 'Port Delivery', 'href' => '/our-services/port-delivery'],
                ['label' => 'Operations & Logistics', 'href' => '/our-services/operations-logistics'],
            ],
            'spare_parts_title' => 'Your Spare Parts',
            'spare_parts_text' => 'Find your spare parts',
            'spare_parts_button_label' => 'Your spare parts',
            'brochures_title' => 'Brochures',
            'brochures_text' => 'Download our company brochure from the link below',
            'brochure_label' => 'Download Brochure PDF',
            'brochure_url' => '#',
            'quote_title' => 'Get a Quote',
        ];
    }

    /**
     * @return object{
     *   categories_title: string,
     *   groups: list<array{id: string, label: string, open: bool, children: list<array{label: string, href: string}>}>,
     *   links: list<array{label: string, href: string}>,
     *   spare_parts_title: string,
     *   spare_parts_text: string,
     *   spare_parts_button_label: string,
     *   brochures_title: string,
     *   brochures_text: string,
     *   brochure_label: string,
     *   brochure_url: string,
     *   quote_title: string,
     * }
     */
    public static function resolvedForPublic(?string $openNavGroupId = null, ?string $activePagePath = null): object
    {
        $row = self::query()->first();
        $defaults = self::defaultContent();

        $categoriesTitle = filled($row?->categories_title)
            ? $row->categories_title
            : ($defaults['categories_title'] ?? 'Our Service Categories');

        $groups = self::normalizeGroups($row?->nav_groups ?? $defaults['nav_groups'] ?? [], $openNavGroupId);
        $links = self::normalizeLinks($row?->nav_links ?? $defaults['nav_links'] ?? [], $activePagePath);

        return (object) [
            'categories_title' => $categoriesTitle,
            'groups' => $groups,
            'links' => $links,
            'spare_parts_title' => filled($row?->spare_parts_title) ? $row->spare_parts_title : ($defaults['spare_parts_title'] ?? 'Your Spare Parts'),
            'spare_parts_text' => filled($row?->spare_parts_text) ? $row->spare_parts_text : ($defaults['spare_parts_text'] ?? ''),
            'spare_parts_button_label' => filled($row?->spare_parts_button_label) ? $row->spare_parts_button_label : ($defaults['spare_parts_button_label'] ?? 'Your spare parts'),
            'brochures_title' => filled($row?->brochures_title) ? $row->brochures_title : ($defaults['brochures_title'] ?? 'Brochures'),
            'brochures_text' => filled($row?->brochures_text) ? $row->brochures_text : ($defaults['brochures_text'] ?? ''),
            'brochure_label' => filled($row?->brochure_label) ? $row->brochure_label : ($defaults['brochure_label'] ?? 'Download Brochure PDF'),
            'brochure_url' => filled($row?->brochure_url) ? $row->brochure_url : ($defaults['brochure_url'] ?? '#'),
            'quote_title' => filled($row?->quote_title) ? $row->quote_title : ($defaults['quote_title'] ?? 'Get a Quote'),
        ];
    }

    /**
     * @param  list<array{id: string, label: string, open: bool, children: list<array{label: string, href: string}>}>  $groups
     */
    private static function normalizeGroups(mixed $raw, ?string $openNavGroupId): array
    {
        if (! is_array($raw)) {
            return [];
        }

        $groups = [];
        foreach ($raw as $group) {
            if (! is_array($group)) {
                continue;
            }
            $id = trim((string) ($group['id'] ?? ''));
            $label = trim((string) ($group['label'] ?? ''));
            if ($id === '' || $label === '') {
                continue;
            }

            $children = [];
            foreach ($group['children'] ?? [] as $child) {
                if (! is_array($child)) {
                    continue;
                }
                $childLabel = trim((string) ($child['label'] ?? ''));
                if ($childLabel === '') {
                    continue;
                }
                $children[] = [
                    'label' => $childLabel,
                    'href' => self::resolveHref((string) ($child['href'] ?? '#')),
                ];
            }

            $groups[] = [
                'id' => $id,
                'label' => $label,
                'open' => $openNavGroupId !== null && $openNavGroupId === $id,
                'children' => $children,
            ];
        }

        if ($openNavGroupId !== null && $groups !== [] && ! collect($groups)->contains(fn ($g) => $g['open'])) {
            $groups[0]['open'] = true;
        }

        return $groups;
    }

    /**
     * @return list<array{label: string, href: string, is_active: bool}>
     */
    private static function normalizeLinks(mixed $raw, ?string $activePagePath): array
    {
        if (! is_array($raw)) {
            return [];
        }

        $currentPath = $activePagePath !== null
            ? (rtrim($activePagePath, '/') === '' ? '/' : rtrim($activePagePath, '/'))
            : null;

        $links = [];
        foreach ($raw as $link) {
            if (! is_array($link)) {
                continue;
            }
            $label = trim((string) ($link['label'] ?? ''));
            if ($label === '') {
                continue;
            }
            $rawHref = (string) ($link['href'] ?? '#');
            $href = self::resolveHref($rawHref);
            $linkPath = self::hrefToPath($rawHref);
            $links[] = [
                'label' => $label,
                'href' => $href,
                'is_active' => $currentPath !== null && $linkPath !== null && $currentPath === $linkPath,
            ];
        }

        return $links;
    }

    private static function hrefToPath(string $href): ?string
    {
        $href = trim($href);
        if ($href === '' || $href === '#') {
            return null;
        }
        if (preg_match('#^https?://#i', $href)) {
            $path = parse_url($href, PHP_URL_PATH);

            return $path !== null && $path !== ''
                ? (rtrim($path, '/') === '' ? '/' : rtrim($path, '/'))
                : null;
        }

        $path = str_starts_with($href, '/') ? $href : '/'.$href;

        return rtrim($path, '/') === '' ? '/' : rtrim($path, '/');
    }

    private static function resolveHref(string $href): string
    {
        $href = trim($href);
        if ($href === '' || $href === '#') {
            return '#';
        }
        if (preg_match('#^https?://#i', $href)) {
            return $href;
        }

        $path = str_starts_with($href, '/') ? $href : '/'.$href;
        $normalized = rtrim($path, '/') === '' ? '/' : rtrim($path, '/');

        return match ($normalized) {
            '/ship-supply' => route('ship-supply'),
            '/technical-stores' => route('technical-stores'),
            '/our-services' => route('our-services'),
            '/get-a-quote' => route('quote.request'),
            '/contact' => route('contact.create'),
            default => ServicePage::publicHrefForPath($normalized) ?? $path,
        };
    }
}
