<?php

namespace App\Support;

final class MapEmbed
{
    /**
     * @return object{type: 'none'|'iframe'|'src', html?: string, src?: string, title?: string}
     */
    public static function resolve(?string $embed, ?string $query, string $title = 'Map'): object
    {
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

        $query = trim((string) $query);
        if ($query !== '') {
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
