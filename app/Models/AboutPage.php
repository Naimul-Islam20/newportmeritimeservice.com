<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
     * Default copy and assets when DB fields are empty (matches original static page).
     *
     * @return array<string, string>
     */
    public static function defaultContent(): array
    {
        return [
            'hero_title' => 'About Us',
            'hero_background' => 'https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=2000&auto=format&fit=crop',
            'meta_description' => 'Learn more about Newport Maritime Service and our commitment to excellence.',
            'trust_image' => 'https://images.unsplash.com/photo-1580674684081-7617fbf3d745?q=80&w=1000&auto=format&fit=crop',
            'trust_title' => "Built on Trust.\nDriven by Excellence.",
            'trust_description' => "Founded in 2012, Newport Maritime Service has grown into one of Bangladesh's most trusted maritime companies. Over more than a decade, we have earned a strong reputation as a dependable General Ship Supplier, Marine Spares Exporter, and Ship Repair Service provider — built on a consistent commitment to quality, efficiency, and client satisfaction.\n\nOur global relationships reflect the trust the maritime industry places in us. We understand the demands of vessel operations firsthand, and we deliver comprehensive, tailored solutions designed to keep your fleet running smoothly.",
            'stat1_value' => '6',
            'stat1_label' => 'Offices & Warehouses',
            'stat2_value' => '150+',
            'stat2_label' => 'Employees',
            'stat3_value' => '25',
            'stat3_label' => 'Trucks',
            'mission_title' => 'Our Mission',
            'mission_body' => 'At Newport Maritime Service, our mission is to ensure uninterrupted vessel operations across Bangladeshi ports by delivering government-certified, round-the-clock marine solutions. From marine spares and ship supplies to waste management and technical services, we are committed to providing every client with unwavering reliability, competitive value, and full regulatory compliance.',
            'vision_title' => 'Our Vision',
            'vision_body' => 'Our vision is to redefine maritime support across South Asia by becoming the most trusted single-source partner for global fleets. We are building a future where operational excellence, environmental responsibility, and long-term client partnerships go hand in hand — driving sustainable growth and establishing Newport Maritime Service as a symbol of industry leadership.',
            'cta_eyebrow' => '13 Years of Experience',
            'cta_heading' => "We're NewPort, a ship supply company with a proud history.",
            'cta_background' => 'https://images.unsplash.com/photo-1553413077-190dd305871c?q=80&w=2000&auto=format&fit=crop',
            'cta_button_label' => 'Watch our story',
            'cta_video_url' => '',
        ];
    }

    public static function resolvedForPublic(): \stdClass
    {
        $defaults = self::defaultContent();
        $row = self::query()->first();

        $out = $defaults;
        if ($row) {
            foreach (array_keys($defaults) as $key) {
                $v = $row->{$key};
                if (is_string($v) && $v !== '') {
                    $out[$key] = $v;
                }
            }
        }

        return (object) $out;
    }

    /** Use full URL, site path, or storage path as image src. */
    public static function imageSrc(string $value): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }
        if (preg_match('#^https?://#i', $value)) {
            return $value;
        }

        return asset(ltrim($value, '/'));
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
        if (preg_match('#(?:(?:www\.|m\.)?youtube\.com/watch\?[^#]*v=|youtube\.com/embed/|youtu\.be/)([a-zA-Z0-9_-]{11})#', $v, $m)) {
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
        $first = self::query()->first();
        if ($first) {
            return $first;
        }

        $defaults = self::defaultContent();
        unset($defaults['meta_description']);

        return self::query()->create($defaults);
    }
}
