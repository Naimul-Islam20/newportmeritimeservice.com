<?php

namespace App\Models;

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
    ];

    protected function casts(): array
    {
        return [
            'steps' => 'array',
        ];
    }

    /**
     * @return array{
     *     mini_title: string,
     *     title: string,
     *     map_image_path: null,
     *     highlight_title: string,
     *     highlight_description: string,
     *     steps: list<string>
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
        ];
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
     *     steps: list<string>
     * }
     */
    public static function displayPayload(): array
    {
        $defaults = self::defaultAttributes();
        $row = self::query()->first();

        if (! $row) {
            return $defaults;
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

        return [
            'mini_title' => $row->mini_title ?? $defaults['mini_title'],
            'title' => $row->title ?? $defaults['title'],
            'map_image_path' => $mapPath,
            'highlight_title' => $row->highlight_title ?? $defaults['highlight_title'],
            'highlight_description' => $row->highlight_description ?? $defaults['highlight_description'],
            'steps' => $steps,
        ];
    }
}
