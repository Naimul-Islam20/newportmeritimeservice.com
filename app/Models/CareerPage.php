<?php

namespace App\Models;

use App\Support\HasManagedPageUploads;
use Illuminate\Database\Eloquent\Model;

class CareerPage extends Model
{
    use HasManagedPageUploads;

    private const UPLOAD_PREFIX = 'career-page';

    private const DEFAULT_HERO = 'https://images.unsplash.com/photo-1521791136064-7986c2920216?q=80&w=2070&auto=format&fit=crop';

    private const DEFAULT_ASIDE = 'https://images.unsplash.com/photo-1521791136064-7986c2920216?q=80&w=900&auto=format&fit=crop';

    protected $fillable = [
        'hero_title',
        'hero_background',
        'meta_description',
        'eyebrow',
        'section_title',
        'intro_paragraphs',
        'application_title',
        'application_lead',
        'qualifications',
        'application_note',
        'hr_email',
        'mail_button_label',
        'kariyer_url',
        'kariyer_button_label',
        'linkedin_url',
        'linkedin_button_label',
        'aside_image',
        'aside_image_alt',
        'team_button_label',
        'team_button_url',
        'offers_eyebrow',
        'offers_title',
        'offers_card_title',
        'offers_paragraphs',
        'bottom_cta_label',
        'bottom_cta_url',
    ];

    protected function casts(): array
    {
        return [
            'intro_paragraphs' => 'array',
            'qualifications' => 'array',
            'offers_paragraphs' => 'array',
        ];
    }

    public static function singleton(): self
    {
        return self::query()->firstOrCreate([], self::defaultContent());
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaultContent(): array
    {
        $siteName = SiteDetail::resolvedSiteName();

        return [
            'hero_title' => 'Career',
            'meta_description' => 'Career opportunities at '.$siteName.' — HR vision, general applications and open positions.',
            'eyebrow' => 'Career',
            'section_title' => 'Our Human Resources vision',
            'intro_paragraphs' => [
                'To be the most preferred company with innovative and sustainable HR practices that make its employees feel valued, create a common culture.',
                'Our aim is to employ employees who adopt '.$siteName.' values, who have the competencies required by the dynamism of the company, who know the importance of the customer, who are willing to learn and develop, who can sustain high performance in the long term, to contribute their professional and personal development, and enable them to use their potential in the most efficient way.',
            ],
            'application_title' => 'General Application',
            'application_lead' => 'The primary qualifications we consider in candidates who want to join us;',
            'qualifications' => [
                'Reliability',
                'Competence',
                'Team Work',
                'Responsibility Awareness and Business Follow-Up',
                'Customer Focused Service',
            ],
            'application_note' => "For open positions, please visit our Kariyer.net and Linkedin pages.\nFor general applications send your CV via mail:",
            'hr_email' => 'careers@newportmeritimeservice.com',
            'mail_button_label' => 'Send mail',
            'kariyer_url' => 'https://www.kariyer.net',
            'kariyer_button_label' => 'Go Kariyer.net page',
            'linkedin_url' => null,
            'linkedin_button_label' => 'Go LinkedIn',
            'aside_image_alt' => 'Professional handshake',
            'team_button_label' => 'Our Team',
            'team_button_url' => '/our-team-management',
            'offers_eyebrow' => $siteName.' offers',
            'offers_title' => 'Promising Career Opportunities',
            'offers_card_title' => 'About our company',
            'offers_paragraphs' => [
                'We want to protect and continuously improve our company, where everyone is happy to be a part of it. '.$siteName.' has built its business through the excellence of service and consistent investment on growing.',
                'Maritime Industry is very large and there are thousands of companies functioning in this sector. Everything can change by the time; except the importance of a reliable service.',
                'So, perhaps we are not number one in our sector yet, but with our intense efforts, young and dynamic professional staff and primary principles of honesty and service quality, we do not think it will take such a long time before we see our company as the number one.',
            ],
            'bottom_cta_label' => 'About us',
            'bottom_cta_url' => '/about-us',
        ];
    }

    public static function resolvedForPublic(): \stdClass
    {
        $row = self::singleton();
        $defaults = self::defaultContent();
        $siteDetail = SiteDetail::query()->first();
        $social = is_array($siteDetail?->social_links) ? $siteDetail->social_links : [];
        $defaultLinkedin = filled($social['linkedin'] ?? null) ? trim((string) $social['linkedin']) : 'https://www.linkedin.com';

        $hrEmail = filled($row->hr_email) ? $row->hr_email : ($defaults['hr_email'] ?? 'careers@newportmeritimeservice.com');
        if ($hrEmail === '' && $siteDetail) {
            $emails = is_array($siteDetail->emails) ? array_values(array_filter($siteDetail->emails, fn ($v) => is_string($v) && str_contains($v, '@'))) : [];
            $hrEmail = $emails[0] ?? 'careers@newportmeritimeservice.com';
        }

        $teamUrl = filled($row->team_button_url) ? $row->team_button_url : ($defaults['team_button_url'] ?? '/our-team-management');
        $bottomUrl = filled($row->bottom_cta_url) ? $row->bottom_cta_url : ($defaults['bottom_cta_url'] ?? '/about-us');
        $kariyerUrl = filled($row->kariyer_url)
            ? trim($row->kariyer_url)
            : ($row->kariyer_url === null ? ($defaults['kariyer_url'] ?? null) : null);
        $linkedinUrl = filled($row->linkedin_url)
            ? trim($row->linkedin_url)
            : ($row->linkedin_url === null ? $defaultLinkedin : null);

        return (object) [
            'hero_title' => filled($row->hero_title) ? $row->hero_title : $defaults['hero_title'],
            'hero_background_url' => self::imageUrl($row->hero_background, self::DEFAULT_HERO),
            'meta_description' => filled($row->meta_description) ? $row->meta_description : $defaults['meta_description'],
            'eyebrow' => filled($row->eyebrow) ? $row->eyebrow : $defaults['eyebrow'],
            'section_title' => filled($row->section_title) ? $row->section_title : $defaults['section_title'],
            'intro_paragraphs' => self::nonEmptyList($row->intro_paragraphs, $defaults['intro_paragraphs'] ?? []),
            'application_title' => filled($row->application_title) ? $row->application_title : $defaults['application_title'],
            'application_lead' => filled($row->application_lead) ? $row->application_lead : $defaults['application_lead'],
            'qualifications' => self::nonEmptyList($row->qualifications, $defaults['qualifications'] ?? []),
            'application_note' => filled($row->application_note) ? $row->application_note : ($defaults['application_note'] ?? ''),
            'hr_email' => $hrEmail,
            'mail_button_label' => filled($row->mail_button_label) ? $row->mail_button_label : $defaults['mail_button_label'],
            'kariyer_url' => filled($kariyerUrl) ? $kariyerUrl : null,
            'kariyer_button_label' => filled($row->kariyer_button_label) ? $row->kariyer_button_label : $defaults['kariyer_button_label'],
            'linkedin_url' => filled($linkedinUrl) ? $linkedinUrl : null,
            'linkedin_button_label' => filled($row->linkedin_button_label) ? $row->linkedin_button_label : $defaults['linkedin_button_label'],
            'aside_image_url' => self::imageUrl($row->aside_image, self::DEFAULT_ASIDE),
            'aside_image_alt' => filled($row->aside_image_alt) ? $row->aside_image_alt : $defaults['aside_image_alt'],
            'team_button_label' => filled($row->team_button_label) ? $row->team_button_label : $defaults['team_button_label'],
            'team_button_href' => self::publicHref($teamUrl),
            'offers_eyebrow' => filled($row->offers_eyebrow) ? $row->offers_eyebrow : $defaults['offers_eyebrow'],
            'offers_title' => filled($row->offers_title) ? $row->offers_title : $defaults['offers_title'],
            'offers_card_title' => filled($row->offers_card_title) ? $row->offers_card_title : $defaults['offers_card_title'],
            'offers_paragraphs' => self::nonEmptyList($row->offers_paragraphs, $defaults['offers_paragraphs'] ?? []),
            'bottom_cta_label' => filled($row->bottom_cta_label) ? $row->bottom_cta_label : $defaults['bottom_cta_label'],
            'bottom_cta_href' => self::publicHref($bottomUrl),
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

    /**
     * @param  list<string>|null  $saved
     * @param  list<string>  $fallback
     * @return list<string>
     */
    private static function nonEmptyList(?array $saved, array $fallback): array
    {
        $items = self::stringList($saved);

        return $items !== [] ? $items : self::stringList($fallback);
    }

    private static function imageUrl(?string $path, string $fallback): string
    {
        $url = self::imageSrc($path);

        return $url !== '' ? $url : $fallback;
    }

    private static function publicHref(string $url): string
    {
        $url = trim($url);
        if ($url === '' || $url === '#') {
            return '#';
        }
        if (preg_match('#^https?://#i', $url)) {
            return $url;
        }

        $path = str_starts_with($url, '/') ? $url : '/'.$url;

        return match (rtrim($path, '/') ?: '/') {
            '/about-us' => route('about-us'),
            '/our-team-management' => route('our-team-management'),
            default => url($path),
        };
    }
}
