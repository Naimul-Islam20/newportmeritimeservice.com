<?php

namespace App\Models;

use App\Support\HasManagedPageUploads;
use Illuminate\Database\Eloquent\Model;

class OurTeamPage extends Model
{
    use HasManagedPageUploads;

    private const UPLOAD_PREFIX = 'our-team-page';

    private const DEFAULT_HERO_IMAGE = 'https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=2070&auto=format&fit=crop';

    private const DEFAULT_MEMBER_PHOTO = 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=600&auto=format&fit=crop';

    protected $fillable = [
        'hero_title',
        'hero_background',
        'meta_description',
        'breadcrumb_label',
        'page_title',
        'regional_nav',
        'category_nav',
        'team_sections',
    ];

    protected function casts(): array
    {
        return [
            'regional_nav' => 'array',
            'category_nav' => 'array',
            'team_sections' => 'array',
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
            'hero_title' => filled($row?->hero_title) ? $row->hero_title : 'Our Team',
            'hero_background_url' => self::heroImageUrl($row?->hero_background),
            'meta_description' => filled($row?->meta_description) ? $row->meta_description : 'Meet our management team and leadership across maritime supply, provision and operations.',
            'breadcrumb_label' => filled($row?->breadcrumb_label) ? $row->breadcrumb_label : 'Management',
            'page_title' => filled($row?->page_title) ? $row->page_title : 'Management',
            'regional_nav' => self::normalizedNav($row?->regional_nav),
            'category_nav' => self::normalizedNav($row?->category_nav),
            'team_sections' => self::normalizedTeamSections($row?->team_sections),
        ];
    }

    public static function uploadPrefix(): string
    {
        return self::UPLOAD_PREFIX;
    }

    /**
     * @return list<array{label: string, url: string}>
     */
    private static function normalizedNav(mixed $raw): array
    {
        if (! is_array($raw)) {
            return [];
        }

        $out = [];
        foreach ($raw as $item) {
            if (! is_array($item)) {
                continue;
            }
            $label = trim((string) ($item['label'] ?? ''));
            if ($label === '') {
                continue;
            }
            $url = trim((string) ($item['url'] ?? '#'));
            $out[] = ['label' => $label, 'url' => $url !== '' ? $url : '#'];
        }

        return $out;
    }

    /**
     * @return list<array{heading: string, members: list<array{name: string, role: string, email: string, phone: ?string, photo_url: string}>}>
     */
    private static function normalizedTeamSections(mixed $raw): array
    {
        if (! is_array($raw)) {
            return [];
        }

        $out = [];
        foreach ($raw as $section) {
            if (! is_array($section)) {
                continue;
            }
            $heading = trim((string) ($section['heading'] ?? ''));
            $membersRaw = $section['members'] ?? [];
            $members = [];
            if (is_array($membersRaw)) {
                foreach ($membersRaw as $member) {
                    if (! is_array($member)) {
                        continue;
                    }
                    $name = trim((string) ($member['name'] ?? ''));
                    if ($name === '') {
                        continue;
                    }
                    $path = is_string($member['photo_path'] ?? null) ? trim($member['photo_path']) : '';
                    $photoUrl = $path !== '' ? self::imageSrc($path) : self::DEFAULT_MEMBER_PHOTO;
                    $phone = trim((string) ($member['phone'] ?? ''));

                    $members[] = [
                        'name' => $name,
                        'role' => trim((string) ($member['role'] ?? '')),
                        'email' => trim((string) ($member['email'] ?? '')),
                        'phone' => $phone !== '' ? $phone : null,
                        'photo_url' => $photoUrl !== '' ? $photoUrl : self::DEFAULT_MEMBER_PHOTO,
                    ];
                }
            }
            if ($heading === '' && $members === []) {
                continue;
            }
            $out[] = [
                'heading' => $heading,
                'members' => $members,
            ];
        }

        return $out;
    }

    private static function heroImageUrl(?string $path): string
    {
        $url = self::imageSrc($path);

        return $url !== '' ? $url : self::DEFAULT_HERO_IMAGE;
    }
}
