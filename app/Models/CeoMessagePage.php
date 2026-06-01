<?php

namespace App\Models;

use App\Support\HasManagedPageUploads;
use Illuminate\Database\Eloquent\Model;

class CeoMessagePage extends Model
{
    use HasManagedPageUploads;

    private const UPLOAD_PREFIX = 'ceo-message-page';

    private const DEFAULT_HERO_IMAGE = 'https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=2070&auto=format&fit=crop';

    private const DEFAULT_PORTRAIT = 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=900&auto=format&fit=crop';

    protected $fillable = [
        'hero_title',
        'hero_background',
        'meta_description',
        'eyebrow',
        'salutation',
        'paragraphs',
        'signature_name',
        'signature_role',
        'portrait_path',
        'linkedin_url',
        'instagram_url',
    ];

    protected function casts(): array
    {
        return [
            'paragraphs' => 'array',
        ];
    }

    public static function singleton(): self
    {
        return self::query()->firstOrCreate([]);
    }

    public static function resolvedForPublic(): \stdClass
    {
        $row = self::query()->first();

        $portrait = self::imageSrc($row?->portrait_path);

        return (object) [
            'hero_title' => filled($row?->hero_title) ? $row->hero_title : 'We believe in the future',
            'hero_background_url' => self::heroImageUrl($row?->hero_background),
            'meta_description' => filled($row?->meta_description) ? $row->meta_description : 'A message from our CEO on experience, trust, and our vision for maritime supply and logistics.',
            'eyebrow' => filled($row?->eyebrow) ? $row->eyebrow : 'Message from the CEO',
            'salutation' => filled($row?->salutation) ? $row->salutation : 'Dear Business Partners and Our Esteemed Colleagues;',
            'paragraphs' => self::stringList($row?->paragraphs),
            'signature_name' => filled($row?->signature_name) ? $row->signature_name : null,
            'signature_role' => filled($row?->signature_role) ? $row->signature_role : null,
            'portrait_url' => $portrait !== '' ? $portrait : self::DEFAULT_PORTRAIT,
            'linkedin_url' => filled($row?->linkedin_url) ? $row->linkedin_url : null,
            'instagram_url' => filled($row?->instagram_url) ? $row->instagram_url : null,
        ];
    }

    public static function uploadPrefix(): string
    {
        return self::UPLOAD_PREFIX;
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
