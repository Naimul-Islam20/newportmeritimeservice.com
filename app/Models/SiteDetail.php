<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteDetail extends Model
{
    protected $fillable = [
        'site_name',
        'favicon_path',
        'meta_description',
        'location',
        'map',
        'emails',
        'phones',
        'social_links',
        'default_image_path',
        'header_logo_path',
        'footer_logo_path',
        'theme_brand_navy',
        'theme_brand_navy_mid',
        'theme_brand_accent',
        'theme_brand_accent_hover',
        'theme_brand_topbar_muted',
        'theme_footer_overlay_base',
        'theme_footer_overlay_opacity',
        'theme_section_strip_a',
        'theme_section_strip_b',
    ];

    protected function casts(): array
    {
        return [
            'emails' => 'array',
            'phones' => 'array',
            'social_links' => 'array',
            'theme_footer_overlay_opacity' => 'integer',
        ];
    }

    /**
     * Public site name from Site Details (never falls back to APP_NAME / ERP17).
     */
    public static function resolvedSiteName(?self $detail = null): string
    {
        $detail ??= self::query()->first();
        if (! $detail) {
            return '';
        }

        $name = is_string($detail->site_name ?? null) ? trim($detail->site_name) : '';

        return $name;
    }

    public function siteNameForMeta(): string
    {
        return self::resolvedSiteName($this);
    }

    public static function pageTitle(?string $pageLabel = null, ?self $detail = null): string
    {
        $name = self::resolvedSiteName($detail ?? self::query()->first());
        $label = is_string($pageLabel) ? trim($pageLabel) : '';

        if ($label !== '' && $name !== '') {
            return "{$label} — {$name}";
        }

        return $label !== '' ? $label : $name;
    }

    public static function faviconAssetUrl(?self $detail = null): ?string
    {
        $detail ??= self::query()->first();
        $path = is_string($detail?->favicon_path ?? null) ? trim($detail->favicon_path) : '';

        return $path !== '' ? asset($path) : null;
    }

    public function metaDescriptionForSite(): ?string
    {
        $desc = is_string($this->meta_description ?? null) ? trim($this->meta_description) : '';

        return $desc !== '' ? $desc : null;
    }

    /**
     * @return array{siteMetaName: string, siteMetaDescription: string|null, siteFaviconUrl: string|null}
     */
    public static function metaForViews(?self $detail = null): array
    {
        $detail ??= self::query()->first();

        if (! $detail) {
            return [
                'siteMetaName' => '',
                'siteMetaDescription' => null,
                'siteFaviconUrl' => null,
            ];
        }

        return [
            'siteMetaName' => self::resolvedSiteName($detail),
            'siteMetaDescription' => $detail->metaDescriptionForSite(),
            'siteFaviconUrl' => self::faviconAssetUrl($detail),
        ];
    }

    /**
     * Default hex / opacity for the Site Details form (when DB value is null).
     *
     * @return array<string, int|string>
     */
    public static function defaultThemeFormValues(): array
    {
        return [
            'theme_brand_accent' => '#e9a70e',
            'theme_brand_navy' => '#112a6d',
            'theme_section_strip_a' => '#e0f2fe',
            'theme_section_strip_b' => '#fefce8',
        ];
    }

    /**
     * Resolved #rrggbb for Site Details theme inputs (old input, then DB, then defaults).
     *
     * @return array<string, string>
     */
    public static function themeHexInputsForEdit(self $detail): array
    {
        $themeDef = self::defaultThemeFormValues();
        $keys = [
            'theme_brand_accent',
            'theme_brand_navy',
            'theme_section_strip_a',
            'theme_section_strip_b',
        ];
        $out = [];
        foreach ($keys as $key) {
            $o = old($key);
            if (is_string($o) && preg_match('/^#[0-9A-Fa-f]{6}$/', $o)) {
                $out[$key] = strtolower($o);

                continue;
            }
            $cur = $detail->{$key} ?? null;
            if (is_string($cur) && preg_match('/^#[0-9A-Fa-f]{6}$/', $cur)) {
                $out[$key] = strtolower($cur);

                continue;
            }
            $out[$key] = (string) ($themeDef[$key] ?? '#000000');
        }

        return $out;
    }

    /**
     * @return array<string, string>
     */
    public static function themeCssVariableMap(): array
    {
        return [
            'theme_brand_accent' => '--primary',
            'theme_brand_navy' => '--secondary',
            'theme_section_strip_a' => '--section-strip-a',
            'theme_section_strip_b' => '--section-strip-b',
        ];
    }

    /**
     * CSS custom properties for :root (public site + admin). Merges DB overrides with defaults.
     *
     * @return array<string, string>
     */
    public function themeVariablesForCss(): array
    {
        $formDefaults = self::defaultThemeFormValues();
        $out = [];
        foreach (self::themeCssVariableMap() as $attr => $var) {
            $out[$var] = is_string($formDefaults[$attr] ?? null) ? strtolower((string) $formDefaults[$attr]) : '';
        }

        foreach (self::themeCssVariableMap() as $attr => $var) {
            $v = $this->{$attr};
            if (is_string($v) && preg_match('/^#[0-9A-Fa-f]{6}$/', $v)) {
                $out[$var] = strtolower($v);
            }
        }

        return $out;
    }

    /**
     * @return array<string, string>
     */
    public static function themeVariablesForApp(): array
    {
        $row = self::query()->first();

        return $row ? $row->themeVariablesForCss() : (new self)->themeVariablesForCss();
    }

    /**
     * Same asset as the public site header logo (Site Details → header logo, or default).
     */
    public static function headerLogoAssetUrl(?self $detail = null): string
    {
        $detail ??= self::query()->first();
        $path = is_string($detail?->header_logo_path ?? null) ? trim($detail->header_logo_path) : '';

        return $path !== '' ? asset($path) : asset('newport-logo.png');
    }

    /**
     * @return array{0: int, 1: int, 2: int}|null
     */
    private static function hexToRgb(string $hex): ?array
    {
        if (! preg_match('/^#([0-9A-Fa-f]{6})$/', $hex, $m)) {
            return null;
        }
        $n = hexdec($m[1]);

        return [($n >> 16) & 255, ($n >> 8) & 255, $n & 255];
    }

    /**
     * @param  array{0: int, 1: int, 2: int}  $rgb
     */
    private static function formatRgba(array $rgb, float $alpha): string
    {
        $a = max(0.0, min(1.0, $alpha));
        $aStr = rtrim(rtrim(sprintf('%.4f', $a), '0'), '.');

        return sprintf('rgba(%d, %d, %d, %s)', $rgb[0], $rgb[1], $rgb[2], $aStr);
    }
}
