<?php

namespace App\Models;

use App\Support\AraRegionalMap;
use App\Support\MapEmbed;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class WhereWeArePort extends Model
{
    protected $fillable = [
        'where_we_are_location_id',
        'slug',
        'title',
        'meta_description',
        'body_paragraphs',
        'map_embed',
        'map_query',
        'footer_link_label',
        'footer_link_url',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'body_paragraphs' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(WhereWeAreLocation::class, 'where_we_are_location_id');
    }

    public static function findForPublic(string $locationSlug, string $portSlug): ?self
    {
        $location = WhereWeAreLocation::findBySlug($locationSlug);
        if (! $location) {
            return null;
        }

        $portSlug = Str::slug($portSlug);
        if ($portSlug === '') {
            return null;
        }

        return self::query()
            ->where('where_we_are_location_id', $location->id)
            ->where('slug', $portSlug)
            ->where('is_active', true)
            ->with('location')
            ->first();
    }

    public static function resolvedForPublic(string $locationSlug, string $portSlug): ?\stdClass
    {
        $port = self::findForPublic($locationSlug, $portSlug);
        if (! $port || ! $port->location) {
            return null;
        }

        $parent = $port->location;

        return (object) [
            'slug' => $port->slug,
            'title' => $port->title,
            'meta_description' => $port->meta_description,
            'body_paragraphs' => self::stringList($port->body_paragraphs),
            'footer_link_label' => $port->footer_link_label,
            'footer_link_href' => filled($port->footer_link_url)
                ? WhereWeAreLocation::publicHrefStatic($port->footer_link_url)
                : null,
            'parent_slug' => $parent->slug,
            'parent_title' => $parent->hero_title,
            'parent_href' => route('where-we-are.location', $parent->slug),
            'hero_title' => $parent->hero_title,
            'hero_background_url' => WhereWeAreLocation::imageUrlStatic(
                $parent->hero_background,
                'https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=2070&auto=format&fit=crop',
            ),
            'eyebrow' => 'Locations',
            'sidebar_regions' => WhereWeAreLocation::sidebarRegionsFor($parent->slug, $port->slug),
            'map' => MapEmbed::resolve($port->map_embed, $port->map_query, $port->title),
            'ara_map_markers' => AraRegionalMap::markersForLocation($parent->slug, $port->slug),
            'show_ara_map' => count(AraRegionalMap::markersForLocation($parent->slug, $port->slug)) > 0,
        ];
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
}
