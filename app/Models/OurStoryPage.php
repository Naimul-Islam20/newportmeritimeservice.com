<?php

namespace App\Models;

use App\Support\HasManagedPageUploads;
use Illuminate\Database\Eloquent\Model;

class OurStoryPage extends Model
{
    use HasManagedPageUploads;

    private const UPLOAD_PREFIX = 'our-story-page';

    private const DEFAULT_HERO_IMAGE = 'https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=2070&auto=format&fit=crop';

    protected $fillable = [
        'hero_title',
        'hero_background',
        'meta_description',
        'eyebrow',
        'section_title',
        'intro_paragraphs',
        'milestones',
    ];

    protected function casts(): array
    {
        return [
            'intro_paragraphs' => 'array',
            'milestones' => 'array',
        ];
    }

    public static function singleton(): self
    {
        return self::query()->firstOrCreate([]);
    }

    public static function resolvedForPublic(): \stdClass
    {
        $row = self::query()->first();

        return (object) [
            'hero_title' => filled($row?->hero_title) ? $row->hero_title : 'Our Story',
            'hero_background_url' => self::heroImageUrl($row?->hero_background),
            'meta_description' => filled($row?->meta_description) ? $row->meta_description : 'Our story since 1992 — maritime supply, provision and logistics across ports worldwide.',
            'eyebrow' => filled($row?->eyebrow) ? $row->eyebrow : 'Our Story',
            'section_title' => filled($row?->section_title) ? $row->section_title : 'Since 1992',
            'intro_paragraphs' => self::stringList($row?->intro_paragraphs),
            'milestones' => self::normalizedMilestones($row?->milestones),
        ];
    }

    public static function uploadPrefix(): string
    {
        return self::UPLOAD_PREFIX;
    }

    /**
     * @return list<array{year: string, title: string, text: string, image_url: string}>
     */
    private static function normalizedMilestones(mixed $raw): array
    {
        if (! is_array($raw)) {
            return [];
        }

        $out = [];
        foreach ($raw as $item) {
            if (! is_array($item)) {
                continue;
            }
            $year = trim((string) ($item['year'] ?? ''));
            $title = trim((string) ($item['title'] ?? ''));
            $text = trim((string) ($item['text'] ?? ''));
            if ($year === '' && $title === '' && $text === '') {
                continue;
            }
            $path = is_string($item['image_path'] ?? null) ? trim($item['image_path']) : '';
            $imageUrl = $path !== '' ? self::imageSrc($path) : self::DEFAULT_HERO_IMAGE;
            $out[] = [
                'year' => $year,
                'title' => $title,
                'text' => $text,
                'image_url' => $imageUrl !== '' ? $imageUrl : self::DEFAULT_HERO_IMAGE,
            ];
        }

        return $out;
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

    private static function heroImageUrl(?string $path): string
    {
        $url = self::imageSrc($path);

        return $url !== '' ? $url : self::DEFAULT_HERO_IMAGE;
    }
}
