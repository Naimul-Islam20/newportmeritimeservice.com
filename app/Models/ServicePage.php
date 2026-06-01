<?php

namespace App\Models;

use App\Support\HasManagedPageUploads;
use Illuminate\Database\Eloquent\Model;

class ServicePage extends Model
{
    use HasManagedPageUploads;

    private const UPLOAD_PREFIX = 'service-pages';

    private const DEFAULT_HERO = 'https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=2070&auto=format&fit=crop';

    private const DEFAULT_CONTENT_IMAGE = 'https://images.unsplash.com/photo-1553413077-190dd305871c?q=80&w=1400&auto=format&fit=crop';

    /** @var list<string> */
    private const DEFAULT_GALLERY_URLS = [
        'https://images.unsplash.com/photo-1553413077-190dd305871c?q=80&w=1400&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=1400&auto=format&fit=crop',
    ];

    protected $fillable = [
        'slug',
        'content_layout',
        'path',
        'open_nav_group_id',
        'hero_title',
        'hero_background',
        'breadcrumb_label',
        'meta_description',
        'eyebrow',
        'title',
        'subtitle',
        'gallery_images',
        'lead_paragraph',
        'body_paragraphs',
        'highlight_paragraph',
        'services_heading',
        'service_columns',
        'content_image',
        'why_heading',
        'why_paragraphs',
        'why_cards',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'body_paragraphs' => 'array',
            'gallery_images' => 'array',
            'service_columns' => 'array',
            'why_paragraphs' => 'array',
            'why_cards' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function uploadPrefix(): string
    {
        return self::UPLOAD_PREFIX;
    }

    /**
     * @return list<string>
     */
    public static function dedicatedPaths(): array
    {
        return self::query()
            ->where('is_active', true)
            ->pluck('path')
            ->map(fn (string $p) => rtrim($p, '/') === '' ? '/' : '/'.ltrim(rtrim($p, '/'), '/'))
            ->values()
            ->all();
    }

    public static function publicHrefForPath(string $path): ?string
    {
        $normalized = rtrim($path, '/') === '' ? '/' : rtrim($path, '/');
        $page = self::query()
            ->where('is_active', true)
            ->where(function ($q) use ($normalized): void {
                $q->where('path', $normalized)
                    ->orWhere('path', ltrim($normalized, '/'));
            })
            ->first();

        if (! $page) {
            return null;
        }

        return self::publicUrlForSlug($page->slug);
    }

    public static function publicUrlForSlug(string $slug): string
    {
        return match ($slug) {
            'technical-stores' => route('technical-stores'),
            'provision' => route('ship-supply'),
            'what-we-do' => route('our-services'),
            'transit-delivery' => route('service.transit-delivery'),
            'port-delivery' => route('service.port-delivery'),
            'operations-logistics' => route('service.operations-logistics'),
            default => url(self::query()->where('slug', $slug)->value('path') ?? '/'),
        };
    }

    public static function findByPath(string $path): ?self
    {
        $normalized = rtrim($path, '/') === '' ? '/' : rtrim($path, '/');
        $alt = ltrim($normalized, '/');

        return self::query()
            ->where('is_active', true)
            ->where(function ($q) use ($normalized, $alt): void {
                $q->where('path', $normalized)->orWhere('path', $alt);
            })
            ->first();
    }

    public static function resolvedForPublic(string $slug): \stdClass
    {
        $row = self::query()->where('slug', $slug)->where('is_active', true)->first();
        $defaults = self::defaultForSlug($slug);

        $serviceColumns = self::normalizeServiceColumns($row?->service_columns ?? $defaults['service_columns'] ?? []);
        $whyCards = self::normalizeWhyCards($row?->why_cards ?? $defaults['why_cards'] ?? []);
        $contentLayout = filled($row?->content_layout) ? $row->content_layout : ($defaults['content_layout'] ?? 'full');

        return (object) [
            'slug' => $slug,
            'content_layout' => $contentLayout,
            'path' => $row?->path ?? $defaults['path'],
            'open_nav_group_id' => $row?->open_nav_group_id ?? $defaults['open_nav_group_id'],
            'hero_title' => filled($row?->hero_title) ? $row->hero_title : $defaults['hero_title'],
            'hero_background_url' => self::imageUrl($row?->hero_background, self::DEFAULT_HERO),
            'breadcrumb_label' => filled($row?->breadcrumb_label) ? $row->breadcrumb_label : $defaults['breadcrumb_label'],
            'meta_description' => filled($row?->meta_description) ? $row->meta_description : $defaults['meta_description'],
            'eyebrow' => filled($row?->eyebrow) ? $row->eyebrow : ($defaults['eyebrow'] ?? null),
            'title' => filled($row?->title) ? $row->title : $defaults['title'],
            'subtitle' => filled($row?->subtitle) ? $row->subtitle : ($defaults['subtitle'] ?? null),
            'gallery_image_urls' => $contentLayout === 'simple'
                ? []
                : self::galleryImageUrls($row?->gallery_images ?? $defaults['gallery_images'] ?? null),
            'lead_paragraph' => filled($row?->lead_paragraph) ? $row->lead_paragraph : $defaults['lead_paragraph'],
            'body_paragraphs' => self::stringList($row?->body_paragraphs ?? $defaults['body_paragraphs']),
            'highlight_paragraph' => filled($row?->highlight_paragraph) ? $row->highlight_paragraph : ($defaults['highlight_paragraph'] ?? ''),
            'services_heading' => filled($row?->services_heading) ? $row->services_heading : $defaults['services_heading'],
            'service_columns' => $serviceColumns,
            'content_image_url' => self::imageUrl($row?->content_image, self::DEFAULT_CONTENT_IMAGE),
            'why_heading' => filled($row?->why_heading) ? $row->why_heading : $defaults['why_heading'],
            'why_paragraphs' => self::stringList($row?->why_paragraphs ?? $defaults['why_paragraphs']),
            'why_cards' => $whyCards,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaultForSlug(string $slug): array
    {
        $siteName = SiteDetail::resolvedSiteName();

        $technicalDefaults = [
            'content_layout' => 'full',
            'path' => '/technical-stores',
            'open_nav_group_id' => 'technical-stores',
            'hero_title' => 'Technical Stores',
            'breadcrumb_label' => 'Technical Stores',
            'meta_description' => 'Technical stores, engine stores, deck supplies, safety equipment and certified marine spare parts for vessels worldwide.',
            'eyebrow' => 'Our Services',
            'title' => 'Technical Stores',
            'subtitle' => 'Competitive prices and high quality',
            'lead_paragraph' => 'Technical Stores',
            'body_paragraphs' => [
                'Our experienced professionals have been assisting ship owners to obtain the correct items and spare parts at most competitive prices for many years.',
                'Our strengths also include short delivery times, individual customer consultation, and tailored instrumentation solutions.',
                'Our extensive range of equipment meets a vessel’s complete onboard needs. Our products are certified for use in the marine environment and supported with approvals from the major classification societies.',
            ],
            'highlight_paragraph' => "In technical supply, {$siteName} offers an innovative approach and is always looking to find better ways of fulfilling things.",
            'services_heading' => 'Our technical stores services',
            'service_columns' => [
                ['Engine Stores', 'Valves', 'Deck Stores', 'Safety Equipments'],
                ['Cabin Stores', 'Galley Stores', 'Nautical & Stationery Items'],
            ],
            'why_heading' => 'Why Choose Us?',
            'why_paragraphs' => [
                "{$siteName} offers an innovative approach, and is always looking to find better ways of doing things. Our expertise and knowledge has for many years assisted ship owners to obtain the correct items and spare parts at most competitive price.",
                "We ensure that all partners in the purchasing process are satisfied from the purchasing departments to the individual sailors. At {$siteName}, we regard this to be our duty.",
                'Tried and tested components for instrumentation and control equipment and systems help our customers ensure the consistent long-term quality of their products and the efficiency of their production processes.',
            ],
            'why_cards' => [
                ['title' => 'Dedicated teams', 'icon' => 'team'],
                ['title' => 'True partners', 'icon' => 'partners'],
                ['title' => 'Global know-how', 'icon' => 'global'],
                ['title' => 'Focus on innovation', 'icon' => 'innovation'],
            ],
        ];

        return match ($slug) {
            'technical-stores' => $technicalDefaults,
            'provision' => array_merge($technicalDefaults, [
                'path' => '/ship-supply',
                'open_nav_group_id' => 'provision',
                'hero_title' => 'Provision',
                'breadcrumb_label' => 'Provision',
                'meta_description' => 'Provisions, stores, and deck supplies for vessels and port operations.',
                'title' => 'Provision',
                'lead_paragraph' => 'Provision',
                'services_heading' => 'Our provision services',
                'service_columns' => [
                    ['Dry Foods', 'Frozen Foods'],
                    ['Fresh Fruits & Vegetables', 'Beverages'],
                ],
            ]),
            'what-we-do' => array_merge($technicalDefaults, [
                'path' => '/our-services',
                'open_nav_group_id' => null,
                'hero_title' => 'What We Do',
                'breadcrumb_label' => 'What We Do',
                'meta_description' => 'Maritime logistics, documentation, and operational support services.',
                'title' => 'What We Do',
                'lead_paragraph' => 'What We Do',
                'services_heading' => 'Our services',
                'service_columns' => [],
                'why_cards' => [],
            ]),
            'transit-delivery' => array_merge($technicalDefaults, [
                'content_layout' => 'simple',
                'path' => '/our-services/transit-delivery',
                'open_nav_group_id' => null,
                'hero_title' => 'Transit Delivery',
                'breadcrumb_label' => 'Transit Delivery',
                'meta_description' => 'Transit delivery for vessels passing through Turkish straits — supplies delivered without stopping.',
                'eyebrow' => null,
                'title' => 'Transit Delivery',
                'subtitle' => null,
                'lead_paragraph' => null,
                'highlight_paragraph' => null,
                'services_heading' => null,
                'service_columns' => [],
                'gallery_images' => [],
                'body_paragraphs' => [
                    'Turkey is in a strategic position and the Bosphorus & Dardanelles Straits are one of the most important waterways in the world.',
                    'We supply transit vessels without them needing to stop. Provisions and stores are packed on pallets; frozen goods are handled with the care your operation requires.',
                    'We recommend that vessels passing the Straits use our services for efficient, reliable delivery.',
                ],
                'why_heading' => 'Why Choose Us?',
            ]),
            'port-delivery' => array_merge($technicalDefaults, [
                'content_layout' => 'simple',
                'path' => '/our-services/port-delivery',
                'open_nav_group_id' => null,
                'hero_title' => 'Port Delivery',
                'breadcrumb_label' => 'Port Delivery',
                'meta_description' => 'Port delivery services for vessels at anchor and berth.',
                'eyebrow' => null,
                'title' => 'Port Delivery',
                'subtitle' => null,
                'lead_paragraph' => null,
                'highlight_paragraph' => null,
                'services_heading' => null,
                'service_columns' => [],
                'gallery_images' => [],
                'body_paragraphs' => [
                    'Our port delivery teams coordinate supplies directly to your vessel at anchor or berth.',
                    'From documentation to last-mile handling, we keep your port call on schedule.',
                ],
                'why_heading' => 'Why Choose Us?',
            ]),
            'operations-logistics' => array_merge($technicalDefaults, [
                'content_layout' => 'simple',
                'path' => '/our-services/operations-logistics',
                'open_nav_group_id' => null,
                'hero_title' => 'Operations & Logistics',
                'breadcrumb_label' => 'Operations & Logistics',
                'meta_description' => 'Operations and logistics support for maritime supply chains.',
                'eyebrow' => null,
                'title' => 'Operations & Logistics',
                'subtitle' => null,
                'lead_paragraph' => null,
                'highlight_paragraph' => null,
                'services_heading' => null,
                'service_columns' => [],
                'gallery_images' => [],
                'body_paragraphs' => [
                    'We plan and execute logistics so your fleet receives the right goods at the right time.',
                    'Our operations team works with owners, managers, and crews worldwide.',
                ],
                'why_heading' => 'Why Choose Us?',
            ]),
            default => $technicalDefaults,
        };
    }

    /**
     * @return list<list<string>>
     */
    private static function normalizeServiceColumns(mixed $raw): array
    {
        if (! is_array($raw)) {
            return [];
        }

        $columns = [];
        foreach ($raw as $col) {
            if (! is_array($col)) {
                continue;
            }
            $items = array_values(array_filter(array_map(
                fn ($v) => is_string($v) ? trim($v) : '',
                $col,
            ), fn ($v) => $v !== ''));
            if ($items !== []) {
                $columns[] = $items;
            }
        }

        return $columns;
    }

    /**
     * @return list<array{title: string, icon: string}>
     */
    private static function normalizeWhyCards(mixed $raw): array
    {
        if (! is_array($raw)) {
            return [];
        }

        $cards = [];
        foreach ($raw as $card) {
            if (! is_array($card)) {
                continue;
            }
            $title = trim((string) ($card['title'] ?? ''));
            if ($title === '') {
                continue;
            }
            $icon = trim((string) ($card['icon'] ?? 'team'));
            $cards[] = ['title' => $title, 'icon' => $icon !== '' ? $icon : 'team'];
        }

        return $cards;
    }

    /**
     * @return list<string>
     */
    private static function stringList(mixed $raw): array
    {
        if (! is_array($raw)) {
            return [];
        }

        return array_values(array_filter(array_map(
            fn ($v) => is_string($v) ? trim($v) : '',
            $raw,
        ), fn ($v) => $v !== ''));
    }

    private static function imageUrl(?string $path, string $fallback): string
    {
        $url = self::imageSrc($path);

        return $url !== '' ? $url : $fallback;
    }

    /**
     * Up to two images shown in a row below the page title (Gimaş-style).
     *
     * @return list<string>
     */
    private static function galleryImageUrls(mixed $raw): array
    {
        $paths = [];
        if (is_array($raw)) {
            foreach ($raw as $item) {
                if (is_string($item) && trim($item) !== '') {
                    $paths[] = trim($item);
                }
            }
        }

        $urls = [];
        for ($i = 0; $i < 2; $i++) {
            $path = $paths[$i] ?? null;
            $url = $path !== null ? self::imageSrc($path) : '';
            if ($url !== '') {
                $urls[] = $url;
            } elseif (isset(self::DEFAULT_GALLERY_URLS[$i])) {
                $urls[] = self::DEFAULT_GALLERY_URLS[$i];
            }
        }

        return $urls;
    }
}
