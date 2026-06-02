<?php

namespace App\Models;

use App\Support\AraRegionalMap;
use App\Support\HasManagedPageUploads;
use App\Support\MapEmbed;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class WhereWeAreLocation extends Model
{
    use HasManagedPageUploads;

    private const UPLOAD_PREFIX = 'where-we-are';

    private const DEFAULT_HERO = 'https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=2070&auto=format&fit=crop';

    /** @var array<string, int> */
    private const REGION_SORT = [
        'TURKEY' => 0,
        'ROTTERDAM' => 1,
        'HAMBURG' => 2,
        'ATHENS' => 3,
    ];

    protected $fillable = [
        'slug',
        'sub_menu_id',
        'hero_title',
        'region_label',
        'sidebar_label',
        'sidebar_extras',
        'hero_background',
        'meta_description',
        'eyebrow',
        'office_title',
        'body_paragraphs',
        'gallery_images',
        'map_embed',
        'map_query',
        'body_link_label',
        'body_link_url',
        'brochure_label',
        'brochure_url',
        'brochure_lead',
        'brochure_file',
        'show_quality_block',
        'quality_block_title',
        'quality_block_lead',
        'certificate_group_slug',
        'membership_group_slug',
        'contact_cta_label',
        'contact_cta_url',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'body_paragraphs' => 'array',
            'sidebar_extras' => 'array',
            'gallery_images' => 'array',
            'show_quality_block' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function subMenu(): BelongsTo
    {
        return $this->belongsTo(SubMenu::class, 'sub_menu_id');
    }

    public function ports(): HasMany
    {
        return $this->hasMany(WhereWeArePort::class)->orderBy('sort_order')->orderBy('id');
    }

    public function activePorts(): HasMany
    {
        return $this->ports()->where('is_active', true);
    }

    public static function findBySlug(string $slug): ?self
    {
        $slug = Str::slug($slug);

        if ($slug === '') {
            return null;
        }

        return self::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }

    public static function resolvedForPublic(string $slug): ?\stdClass
    {
        $row = self::findBySlug($slug);

        if (! $row) {
            return null;
        }

        $contactUrl = filled($row->contact_cta_url) ? $row->contact_cta_url : '/contact';
        $brochureUrl = filled($row->brochure_file)
            ? self::brochureFileUrl($row->brochure_file)
            : (filled($row->brochure_url) ? $row->brochure_url : null);

        $galleryUrls = self::galleryUrls($row->gallery_images);
        $certificateGroup = self::certificateGroupForSlug($row->certificate_group_slug);
        $membershipGroup = self::certificateGroupForSlug($row->membership_group_slug);
        $showQuality = (bool) $row->show_quality_block
            && ($certificateGroup !== null || $membershipGroup !== null || filled($row->quality_block_title));

        return (object) [
            'slug' => $row->slug,
            'hero_title' => $row->hero_title,
            'hero_background_url' => self::imageUrl($row->hero_background, self::DEFAULT_HERO),
            'meta_description' => $row->meta_description,
            'eyebrow' => filled($row->eyebrow) ? $row->eyebrow : 'Where We Are',
            'office_title' => $row->office_title,
            'body_paragraphs' => self::stringList($row->body_paragraphs),
            'body_link_label' => $row->body_link_label,
            'body_link_href' => filled($row->body_link_url) ? self::publicHref($row->body_link_url) : null,
            'gallery_image_urls' => $galleryUrls,
            'has_gallery' => count($galleryUrls) > 0,
            'brochure_label' => $row->brochure_label,
            'brochure_href' => $brochureUrl,
            'show_quality_block' => $showQuality,
            'quality_block_title' => filled($row->quality_block_title)
                ? $row->quality_block_title
                : 'Quality Certificates & Memberships',
            'quality_block_lead' => $row->quality_block_lead,
            'certificate_group' => $certificateGroup,
            'membership_group' => $membershipGroup,
            'contact_cta_label' => filled($row->contact_cta_label) ? $row->contact_cta_label : 'Contact Us',
            'contact_cta_href' => self::publicHref($contactUrl),
            'quality_certificates_href' => route('quality-certificates'),
            'sidebar_regions' => self::sidebarRegionsFor($row->slug, null),
            'map' => MapEmbed::resolve($row->map_embed, $row->map_query, $row->hero_title),
            'ara_map_markers' => AraRegionalMap::markersForLocation($row->slug, null),
            'show_ara_map' => count(AraRegionalMap::markersForLocation($row->slug, null)) > 0,
        ];
    }

    /**
     * Gimaş-style sidebar: all regions with office links, ARA port accordion, extras, brochure.
     *
     * @return list<object{label: string, items: list<object>, brochure: ?object}>
     */
    public static function sidebarRegionsFor(string $currentLocationSlug, ?string $currentPortSlug = null): array
    {
        $currentLocationSlug = Str::slug($currentLocationSlug);
        $currentPortSlug = $currentPortSlug !== null ? Str::slug($currentPortSlug) : null;

        $locations = self::query()
            ->where('is_active', true)
            ->with(['ports' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')->orderBy('id')])
            ->orderBy('region_label')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $byRegion = [];
        $regionExtras = [];
        /** @var array<string, WhereWeAreLocation> $portAccordionParents */
        $portAccordionParents = [];

        foreach ($locations as $loc) {
            $region = strtoupper(trim((string) ($loc->region_label ?: $loc->hero_title)));
            if ($region === '') {
                $region = 'LOCATIONS';
            }

            if (! isset($byRegion[$region])) {
                $byRegion[$region] = [
                    'label' => $region,
                    'items' => [],
                    'brochure' => null,
                    'sort' => self::REGION_SORT[$region] ?? (100 + $loc->sort_order),
                ];
                $regionExtras[$region] = [];
            }

            $hasPorts = $loc->ports->isNotEmpty();
            if ($hasPorts) {
                $portAccordionParents[$region] = $loc;
            } else {
                $byRegion[$region]['items'][] = (object) [
                    'type' => 'link',
                    'label' => filled($loc->sidebar_label)
                        ? $loc->sidebar_label
                        : trim($loc->hero_title.' '.($loc->office_title ?? '')),
                    'href' => route('where-we-are.location', $loc->slug),
                    'is_active' => $currentPortSlug === null && $loc->slug === $currentLocationSlug,
                ];
            }

            foreach (self::sidebarExtrasList($loc->sidebar_extras) as $extra) {
                $key = $extra['label'];
                if (! isset($regionExtras[$region][$key])) {
                    $regionExtras[$region][$key] = $extra;
                }
            }

            if (
                filled($loc->brochure_lead)
                && (filled($loc->brochure_file) || filled($loc->brochure_url))
                && $byRegion[$region]['brochure'] === null
            ) {
                $brochureHref = filled($loc->brochure_file)
                    ? self::brochureFileUrl($loc->brochure_file)
                    : (filled($loc->brochure_url) ? $loc->brochure_url : null);

                if ($brochureHref) {
                    $byRegion[$region]['brochure'] = (object) [
                        'lead' => $loc->brochure_lead,
                        'label' => filled($loc->brochure_label) ? $loc->brochure_label : 'Download Brochure PDF',
                        'href' => $brochureHref,
                    ];
                }
            }
        }

        foreach ($portAccordionParents as $region => $loc) {
            $children = [];
            foreach ($loc->ports as $port) {
                $children[] = (object) [
                    'label' => $port->title,
                    'href' => route('where-we-are.port', [$loc->slug, $port->slug]),
                    'is_active' => $currentPortSlug === $port->slug,
                ];
            }

            $items = [
                (object) [
                    'type' => 'accordion',
                    'label' => 'Ports in the ARA area',
                    'open' => true,
                    'children' => $children,
                ],
                (object) [
                    'type' => 'link',
                    'label' => filled($loc->sidebar_label)
                        ? $loc->sidebar_label
                        : trim($loc->hero_title.' '.($loc->office_title ?? '')),
                    'href' => route('where-we-are.location', $loc->slug),
                    'is_active' => $currentPortSlug === null && $loc->slug === $currentLocationSlug,
                ],
            ];

            $byRegion[$region]['items'] = array_merge($items, $byRegion[$region]['items']);
        }

        foreach ($byRegion as $region => &$block) {
            foreach ($regionExtras[$region] ?? [] as $extra) {
                $block['items'][] = (object) [
                    'type' => 'link',
                    'label' => $extra['label'],
                    'href' => self::publicHrefStatic($extra['url']),
                    'is_active' => false,
                ];
            }
        }
        unset($block);

        $regions = array_values($byRegion);
        usort($regions, fn ($a, $b) => $a['sort'] <=> $b['sort']);

        return array_map(fn (array $r) => (object) [
            'label' => $r['label'],
            'items' => $r['items'],
            'brochure' => $r['brochure'],
        ], $regions);
    }

    public static function publicHrefStatic(string $url): string
    {
        return self::publicHref($url);
    }

    public static function imageUrlStatic(?string $path, string $fallback): string
    {
        return self::imageUrl($path, $fallback);
    }

    public static function certificateGroupForSlug(?string $slug): ?CertificateGroup
    {
        if (! filled($slug)) {
            return null;
        }

        return CertificateGroup::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with(['activeCertificates'])
            ->first();
    }

    /**
     * @return list<self>
     */
    public static function activeOrdered(): array
    {
        return self::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->all();
    }

    public function syncSubMenu(Menu $whoWeAreMenu, SubMenu $parentWhereWeAre): SubMenu
    {
        $url = '/where-we-are/'.$this->slug;

        $sub = SubMenu::query()->updateOrCreate(
            [
                'menu_id' => $whoWeAreMenu->id,
                'parent_sub_menu_id' => $parentWhereWeAre->id,
                'url' => $url,
            ],
            [
                'label' => $this->hero_title,
                'sort_order' => $this->sort_order,
                'is_active' => $this->is_active,
            ],
        );

        $this->sub_menu_id = $sub->id;
        $this->saveQuietly();

        return $sub;
    }

    public static function uploadPrefix(): string
    {
        return self::UPLOAD_PREFIX;
    }

    private static function brochureFileUrl(?string $path): ?string
    {
        if (! filled($path)) {
            return null;
        }

        $url = self::imageSrc($path);

        return $url !== '' ? $url : null;
    }

    private static function imageUrl(?string $path, string $fallback): string
    {
        $url = self::imageSrc($path);

        return $url !== '' ? $url : $fallback;
    }

    /**
     * @return list<string>
     */
    /**
     * @return list<string>
     */
    private static function galleryUrls(mixed $raw): array
    {
        if (! is_array($raw)) {
            return [];
        }

        return array_values(array_filter(array_map(function ($item) {
            if (is_string($item) && trim($item) !== '') {
                $url = self::imageSrc($item);

                return $url !== '' ? $url : null;
            }

            return null;
        }, $raw)));
    }

    /**
     * @return list<array{label: string, url: string}>
     */
    private static function sidebarExtrasList(mixed $raw): array
    {
        if (! is_array($raw)) {
            return [];
        }

        $out = [];
        foreach ($raw as $item) {
            if (! is_array($item)) {
                continue;
            }
            $label = trim((string) ($item['label'] ?? ''));
            $url = trim((string) ($item['url'] ?? ''));
            if ($label !== '' && $url !== '') {
                $out[] = ['label' => $label, 'url' => $url];
            }
        }

        return $out;
    }

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

    private static function publicHref(string $url): string
    {
        $url = trim($url);
        if ($url === '' || $url === '#') {
            return route('contact.create');
        }
        if (str_starts_with($url, '#')) {
            return $url;
        }
        if (preg_match('#^https?://#i', $url)) {
            return $url;
        }

        $path = str_starts_with($url, '/') ? $url : '/'.$url;

        return match (rtrim($path, '/') ?: '/') {
            '/contact' => route('contact.create'),
            '/about-us' => route('about-us'),
            '/where-we-are' => route('where-we-are'),
            '/locations' => route('locations'),
            '/locations/all-ports-of-turkey' => url('/locations/all-ports-of-turkey'),
            '/locations/ports-in-ara' => url('/locations/ports-in-ara'),
            default => url($path),
        };
    }
}
