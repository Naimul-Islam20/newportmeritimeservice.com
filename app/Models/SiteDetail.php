<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteDetail extends Model
{
    protected $fillable = [
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
     * Default hex / opacity for the Site Details form (when DB value is null).
     *
     * @return array<string, int|string>
     */
    public static function defaultThemeFormValues(): array
    {
        return [
            'theme_brand_navy' => '#112a6d',
            'theme_brand_navy_mid' => '#213b86',
            'theme_brand_accent' => '#3eb0e3',
            'theme_brand_accent_hover' => '#2b9bc9',
            'theme_brand_topbar_muted' => '#b8c6e6',
            'theme_footer_overlay_base' => '#0a1946',
            'theme_footer_overlay_opacity' => 65,
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
            'theme_brand_navy',
            'theme_brand_navy_mid',
            'theme_brand_accent',
            'theme_brand_accent_hover',
            'theme_brand_topbar_muted',
            'theme_footer_overlay_base',
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
            'theme_brand_navy' => '--brand-navy',
            'theme_brand_navy_mid' => '--brand-navy-mid',
            'theme_brand_accent' => '--brand-accent',
            'theme_brand_accent_hover' => '--brand-accent-hover',
            'theme_brand_topbar_muted' => '--brand-topbar-muted',
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
        $out['--brand-footer-overlay'] = 'rgba(10, 25, 70, 0.65)';

        foreach (self::themeCssVariableMap() as $attr => $var) {
            $v = $this->{$attr};
            if (is_string($v) && preg_match('/^#[0-9A-Fa-f]{6}$/', $v)) {
                $out[$var] = strtolower($v);
            }
        }

        $base = $this->theme_footer_overlay_base;
        $op = $this->theme_footer_overlay_opacity;
        if (is_string($base) && preg_match('/^#[0-9A-Fa-f]{6}$/', $base)) {
            $rgb = self::hexToRgb($base);
            if ($rgb !== null) {
                $pct = $op !== null ? max(0, min(100, (int) $op)) : (int) $formDefaults['theme_footer_overlay_opacity'];
                $out['--brand-footer-overlay'] = self::formatRgba($rgb, $pct / 100);
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
