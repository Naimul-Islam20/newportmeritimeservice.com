<?php

namespace App\Support;

final class MapEmbed
{
    /**
     * @return object{type: 'none'|'iframe'|'src', html?: string, src?: string, title?: string}
     */
    public static function resolve(
        ?string $embed,
        ?string $query,
        string $title = 'Map',
        ?float $lat = null,
        ?float $lng = null,
        int $zoom = 18,
    ): object {
        $embed = trim((string) $embed);
        if ($embed !== '' && stripos($embed, '<iframe') !== false) {
            return (object) [
                'type' => 'iframe',
                'html' => $embed,
                'title' => $title,
            ];
        }

        if ($embed !== '' && filter_var($embed, FILTER_VALIDATE_URL)) {
            return (object) [
                'type' => 'src',
                'src' => $embed,
                'title' => $title,
            ];
        }

        if ($lat !== null && $lng !== null) {
            $zoom = max(1, min(21, $zoom));

            return (object) [
                'type' => 'src',
                'src' => sprintf(
                    'https://maps.google.com/maps?q=%F,%F&hl=en&z=%d&output=embed',
                    $lat,
                    $lng,
                    $zoom,
                ),
                'title' => $title,
            ];
        }

        $query = trim((string) $query);
        if ($query !== '') {
            if (preg_match('/^(-?\d+(?:\.\d+)?),\s*(-?\d+(?:\.\d+)?)$/', $query, $matches)) {
                $zoom = max(1, min(21, $zoom));

                return (object) [
                    'type' => 'src',
                    'src' => sprintf(
                        'https://maps.google.com/maps?q=%s,%s&hl=en&z=%d&output=embed',
                        $matches[1],
                        $matches[2],
                        $zoom,
                    ),
                    'title' => $title,
                ];
            }

            return (object) [
                'type' => 'src',
                'src' => 'https://www.google.com/maps?q='.rawurlencode($query).'&output=embed',
                'title' => $title,
            ];
        }

        return (object) ['type' => 'none', 'title' => $title];
    }

    public static function hasDisplay(object $map): bool
    {
        return ($map->type ?? 'none') !== 'none';
    }
}
