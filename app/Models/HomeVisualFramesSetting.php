<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeVisualFramesSetting extends Model
{
    protected $table = 'home_visual_frames_settings';

    protected $fillable = [
        'mini_title',
        'title',
        'description',
        'gallery',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return array{mini_title: string, title: string, description: string}
     */
    public static function defaultHeader(): array
    {
        return [
            'mini_title' => 'Visual Showcase',
            'title' => 'Our World in Frames',
            'description' => 'A glimpse of our ports, fleet support, and supply operations—around the clock from berths and warehouses to open water.',
        ];
    }

    /**
     * Default gallery (URLs only) when no DB row exists — matches previous static home block.
     *
     * @return list<array{path: null, url: string, caption: string}>
     */
    public static function defaultGallery(): array
    {
        return [
            ['path' => null, 'url' => 'https://images.unsplash.com/photo-1577412647305-991150c7d163?q=80&w=1600&auto=format&fit=crop', 'caption' => 'Port Operations'],
            ['path' => null, 'url' => 'https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=1600&auto=format&fit=crop', 'caption' => 'Global Logistics'],
            ['path' => null, 'url' => 'https://images.unsplash.com/photo-1494412574743-01927c452424?q=80&w=1600&auto=format&fit=crop', 'caption' => 'Vessel Supply'],
            ['path' => null, 'url' => 'https://images.unsplash.com/photo-1559139225-30071e443546?q=80&w=1600&auto=format&fit=crop', 'caption' => 'Technical Support'],
            ['path' => null, 'url' => 'https://images.unsplash.com/photo-1518837695005-2083093ee35b?q=80&w=1600&auto=format&fit=crop', 'caption' => 'Marine Services'],
            ['path' => null, 'url' => 'https://images.unsplash.com/photo-1553413077-190dd305871c?q=80&w=1600&auto=format&fit=crop', 'caption' => 'Warehouse and Storage'],
        ];
    }

    /**
     * @param  array<string, mixed>  $item
     */
    public static function resolveItemSrc(array $item): ?string
    {
        $path = isset($item['path']) && is_string($item['path']) ? trim($item['path']) : '';
        if ($path !== '') {
            return asset($path);
        }
        $url = isset($item['url']) && is_string($item['url']) ? trim($item['url']) : '';
        if ($url !== '') {
            return $url;
        }

        return null;
    }

    /**
     * Payload for the home page “Our World in Frames” block.
     *
     * @return array{
     *     mini_title: string|null,
     *     title: string|null,
     *     description: string|null,
     *     items: list<array{src: string, caption: string|null}>,
     *     is_active: bool,
     *     show: bool
     * }
     */
    public static function displayPayload(): array
    {
        $defaults = self::defaultHeader();
        $defaultGallery = self::defaultGallery();

        $row = self::query()->first();

        if (! $row) {
            $items = [];
            foreach ($defaultGallery as $g) {
                $src = self::resolveItemSrc($g);
                if ($src !== null) {
                    $items[] = [
                        'src' => $src,
                        'caption' => isset($g['caption']) && is_string($g['caption']) && $g['caption'] !== '' ? $g['caption'] : null,
                    ];
                }
            }

            return [
                'mini_title' => $defaults['mini_title'],
                'title' => $defaults['title'],
                'description' => $defaults['description'],
                'items' => $items,
                'is_active' => true,
                'show' => true,
            ];
        }

        $mini = $row->mini_title;
        $title = $row->title;
        $description = $row->description;
        $gallery = is_array($row->gallery) ? $row->gallery : [];

        $items = [];
        foreach ($gallery as $g) {
            if (! is_array($g)) {
                continue;
            }
            $src = self::resolveItemSrc($g);
            if ($src === null) {
                continue;
            }
            $cap = $g['caption'] ?? null;
            $items[] = [
                'src' => $src,
                'caption' => is_string($cap) && trim($cap) !== '' ? trim($cap) : null,
            ];
        }

        $hasText = filled($mini) || filled($title) || filled($description);
        $show = (bool) $row->is_active && (count($items) > 0 || $hasText);

        return [
            'mini_title' => is_string($mini) ? $mini : null,
            'title' => is_string($title) ? $title : null,
            'description' => is_string($description) ? $description : null,
            'items' => $items,
            'is_active' => (bool) $row->is_active,
            'show' => $show,
        ];
    }
}
