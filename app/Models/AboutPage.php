<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Support\PublicUploadUrl;
use Illuminate\Support\Facades\Storage;

class AboutPage extends Model
{
    protected $fillable = [
        'hero_title',
        'hero_background',
        'trust_image',
        'trust_title',
        'trust_description',
        'stat1_value',
        'stat1_label',
        'stat2_value',
        'stat2_label',
        'stat3_value',
        'stat3_label',
        'mission_title',
        'mission_body',
        'vision_title',
        'vision_body',
        'mission_image',
        'vision_image',
        'mission_vision_image',
        'cta_eyebrow',
        'cta_heading',
        'cta_background',
        'cta_button_label',
        'cta_video_url',
    ];

    /**
     * Empty placeholders for admin form hints only (not used on the public site).
     *
     * @return array<string, string>
     */
    public static function defaultContent(): array
    {
        $placeholders = [];
        foreach ((new self)->getFillable() as $key) {
            $placeholders[$key] = '';
        }

        return $placeholders;
    }

    /** Public site: database values only — no static fallbacks. */
    public static function resolvedForPublic(): \stdClass
    {
        $row = self::query()->first();
        $out = [];

        foreach ((new self)->getFillable() as $key) {
            $out[$key] = null;
        }

        if (! $row) {
            return (object) $out;
        }

        $imageKeys = ['hero_background', 'trust_image', 'mission_image', 'vision_image', 'mission_vision_image', 'cta_background'];

        foreach ((new self)->getFillable() as $key) {
            $v = $row->{$key};
            if (is_string($v)) {
                $trimmed = trim($v);
                if ($trimmed === '') {
                    $out[$key] = null;

                    continue;
                }
                if (in_array($key, $imageKeys, true) && self::imageSrc($trimmed) === '') {
                    $out[$key] = null;

                    continue;
                }
                $out[$key] = $trimmed;
            } elseif ($v !== null && $v !== '') {
                $out[$key] = $v;
            }
        }

        return (object) $out;
    }

    /**
     * Mission & vision copy for the public home section.
     * Prefers about_pages fields; falls back to stored menu/about page sections.
     *
     * @return object{mission_title: string, mission_body: ?string, vision_title: string, vision_body: ?string}
     */
    public static function missionVisionForPublic(): object
    {
        $about = self::resolvedForPublic();

        $missionTitle = filled($about->mission_title ?? null) ? trim($about->mission_title) : null;
        $missionBody = filled($about->mission_body ?? null) ? trim($about->mission_body) : null;
        $visionTitle = filled($about->vision_title ?? null) ? trim($about->vision_title) : null;
        $visionBody = filled($about->vision_body ?? null) ? trim($about->vision_body) : null;

        if ($missionBody === null || $visionBody === null) {
            $sectionData = self::missionVisionSectionData();
            if ($sectionData !== null) {
                $parsed = self::parseMissionVisionFromSectionData($sectionData);
                if ($missionTitle === null && filled($parsed['mission_title'])) {
                    $missionTitle = trim((string) $parsed['mission_title']);
                }
                if ($visionTitle === null && filled($parsed['vision_title'])) {
                    $visionTitle = trim((string) $parsed['vision_title']);
                }
                if ($missionBody === null && filled($parsed['mission_body'])) {
                    $missionBody = trim((string) $parsed['mission_body']);
                }
                if ($visionBody === null && filled($parsed['vision_body'])) {
                    $visionBody = trim((string) $parsed['vision_body']);
                }
            }
        }

        return (object) [
            'mission_title' => $missionTitle ?? 'Our Mission',
            'mission_body' => $missionBody,
            'vision_title' => $visionTitle ?? 'Our Vision',
            'vision_body' => $visionBody,
        ];
    }

    /** Side image for the Mission & Vision block (legacy single image). */
    public static function missionVisionImageForPublic(): string
    {
        $about = self::resolvedForPublic();

        return self::imageSrc($about->mission_vision_image ?? null);
    }

    public static function missionImageForPublic(): string
    {
        $about = self::resolvedForPublic();
        $mission = self::imageSrc($about->mission_image ?? null);
        if ($mission !== '') {
            return $mission;
        }

        return self::missionVisionImageForPublic();
    }

    public static function visionImageForPublic(): string
    {
        $about = self::resolvedForPublic();
        $vision = self::imageSrc($about->vision_image ?? null);
        if ($vision !== '') {
            return $vision;
        }

        return self::missionVisionImageForPublic();
    }

    /** @return array<string, mixed>|null */
    private static function missionVisionSectionData(): ?array
    {
        $sub = SubMenu::query()
            ->where(function ($q): void {
                $q->where('url', '/our-values-mission-vision')
                    ->orWhere('url', 'our-values-mission-vision');
            })
            ->first();

        if ($sub) {
            $section = $sub->pageSections()
                ->where('type', 'two_column_two_side_details')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->first();

            if ($section && is_array($section->data)) {
                return $section->data;
            }
        }

        $aboutPage = self::query()->first();
        if ($aboutPage) {
            $section = $aboutPage->pageSections()
                ->where('type', 'two_column_two_side_details')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->first();

            if ($section && is_array($section->data)) {
                return $section->data;
            }
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{mission_title: mixed, mission_body: mixed, vision_title: mixed, vision_body: mixed}
     */
    private static function parseMissionVisionFromSectionData(array $data): array
    {
        $leftTitle = strtolower(trim((string) data_get($data, 'left_title', '')));
        $rightTitle = strtolower(trim((string) data_get($data, 'right_title', '')));
        $leftDesc = data_get($data, 'left_description');
        $rightDesc = data_get($data, 'right_description');
        $leftIsVision = str_contains($leftTitle, 'vision');
        $rightIsVision = str_contains($rightTitle, 'vision');

        if ($leftIsVision && ! $rightIsVision) {
            return [
                'mission_title' => data_get($data, 'right_title'),
                'mission_body' => $rightDesc,
                'vision_title' => data_get($data, 'left_title'),
                'vision_body' => $leftDesc,
            ];
        }

        return [
            'mission_title' => data_get($data, 'left_title'),
            'mission_body' => $leftDesc,
            'vision_title' => data_get($data, 'right_title'),
            'vision_body' => $rightDesc,
        ];
    }

    /** Use full URL, site path, or storage path as image src (empty if file missing). */
    public static function imageSrc(?string $value): string
    {
        return PublicUploadUrl::fromPath($value);
    }

    public static function isManagedUploadPath(?string $path): bool
    {
        if (! is_string($path) || $path === '') {
            return false;
        }
        if (preg_match('#^https?://#i', $path)) {
            return false;
        }
        $p = ltrim(str_replace('\\', '/', $path), '/');

        return str_starts_with($p, 'about-page/');
    }

    public static function deleteManagedUpload(?string $path): void
    {
        if (! self::isManagedUploadPath($path)) {
            return;
        }
        $p = ltrim(str_replace('\\', '/', $path), '/');
        if (Storage::disk('public_site')->exists($p)) {
            Storage::disk('public_site')->delete($p);
        }
    }

    /**
     * YouTube-only: modal player uses embed URL. Non-YouTube values are ignored.
     *
     * @return array{type: 'none'|'youtube', embed_url: string}
     */
    public static function videoModalPayload(?string $ctaVideoUrl): array
    {
        $empty = ['type' => 'none', 'embed_url' => ''];
        if (! is_string($ctaVideoUrl) || trim($ctaVideoUrl) === '') {
            return $empty;
        }
        $v = trim($ctaVideoUrl);
        if (preg_match('~(?:(?:www\.|m\.)?youtube\.com/watch\?(?:[^&\s]*&)*v=|youtube\.com/embed/|youtu\.be/)([a-zA-Z0-9_-]{11})~', $v, $m)) {
            $id = $m[1];

            return [
                'type' => 'youtube',
                'embed_url' => 'https://www.youtube.com/embed/'.$id.'?rel=0&autoplay=0',
            ];
        }

        return $empty;
    }

    public static function singleton(): self
    {
        return self::query()->firstOrCreate([]);
    }

    public function pageSections(): MorphMany
    {
        return $this->morphMany(MenuPageSection::class, 'sectionable');
    }
}
