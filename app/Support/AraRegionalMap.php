<?php

namespace App\Support;

use App\Models\WhereWeAreLocation;
use App\Models\WhereWeArePort;

final class AraRegionalMap
{
    /**
     * Marker positions on the ARA overview SVG (viewBox 0 0 640 360).
     *
     * @var array<string, array{x: float, y: float}>
     */
    private const POSITIONS = [
        'port-of-rotterdam' => ['x' => 318, 'y' => 168],
        'port-of-antwerp' => ['x' => 298, 'y' => 182],
        'ghent-seaport' => ['x' => 286, 'y' => 192],
        'port-of-dunkirk' => ['x' => 252, 'y' => 186],
        'port-of-bremen' => ['x' => 368, 'y' => 138],
        'port-of-hamburg' => ['x' => 392, 'y' => 128],
        'port-of-le-havre' => ['x' => 228, 'y' => 198],
    ];

    /**
     * @return list<object{slug: string, label: string, href: string, x: float, y: float, active: bool}>
     */
    public static function markersForLocation(string $locationSlug, ?string $activePortSlug = null): array
    {
        $location = WhereWeAreLocation::findBySlug($locationSlug);
        if (! $location) {
            return [];
        }

        $activePortSlug = $activePortSlug !== null ? \Illuminate\Support\Str::slug($activePortSlug) : null;
        $ports = WhereWeArePort::query()
            ->where('where_we_are_location_id', $location->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $markers = [];
        foreach ($ports as $port) {
            $pos = self::POSITIONS[$port->slug] ?? null;
            if (! $pos) {
                continue;
            }

            $markers[] = (object) [
                'slug' => $port->slug,
                'label' => $port->title,
                'href' => route('where-we-are.port', [$location->slug, $port->slug]),
                'x' => $pos['x'],
                'y' => $pos['y'],
                'active' => $activePortSlug === $port->slug,
            ];
        }

        return $markers;
    }
}
