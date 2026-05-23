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

        $imageKeys = ['hero_background', 'trust_image', 'cta_background'];

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
                'embed_url' => 'https://www.youtube.com/embed/'.$id.'?rel=0',
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
