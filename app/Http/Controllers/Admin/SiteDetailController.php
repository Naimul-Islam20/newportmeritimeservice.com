<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSiteDetailRequest;
use App\Models\SiteDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SiteDetailController extends Controller
{
    public function edit(Request $request): View
    {
        $detail = SiteDetail::query()->first();

        if (! $detail) {
            $detail = SiteDetail::create([
                'location' => null,
                'map' => null,
                'emails' => [],
                'phones' => [],
                'social_links' => [
                    'facebook' => null,
                    'linkedin' => null,
                    'youtube' => null,
                    'twitter' => null,
                ],
                'default_image_path' => null,
            ]);
        }

        $this->authorize('view', $detail);

        return view('admin.site-details.edit', [
            'detail' => $detail,
            'themeDef' => SiteDetail::defaultThemeFormValues(),
            'themeHex' => SiteDetail::themeHexInputsForEdit($detail),
        ]);
    }

    public function update(UpdateSiteDetailRequest $request, SiteDetail $siteDetail): RedirectResponse
    {
        $data = $request->validated();

        $emails = collect($data['emails'] ?? [])
            ->map(fn ($v) => is_string($v) ? trim($v) : '')
            ->filter()
            ->unique()
            ->values()
            ->all();

        $phones = collect($data['phones'] ?? [])
            ->map(fn ($v) => is_string($v) ? trim($v) : '')
            ->filter()
            ->unique()
            ->values()
            ->all();

        $social = $data['social'] ?? [];
        $socialLinks = [
            'facebook' => isset($social['facebook']) && is_string($social['facebook']) ? trim($social['facebook']) : null,
            'linkedin' => isset($social['linkedin']) && is_string($social['linkedin']) ? trim($social['linkedin']) : null,
            'youtube' => isset($social['youtube']) && is_string($social['youtube']) ? trim($social['youtube']) : null,
            'twitter' => isset($social['twitter']) && is_string($social['twitter']) ? trim($social['twitter']) : null,
        ];
        foreach ($socialLinks as $k => $v) {
            if ($v === '') {
                $socialLinks[$k] = null;
            }
        }

        $defaultImagePath = $siteDetail->default_image_path;
        if ($request->hasFile('default_image')) {
            $path = $request->file('default_image')->store('site', 'public_site');

            if (is_string($defaultImagePath) && $defaultImagePath !== '' && Storage::disk('public_site')->exists($defaultImagePath)) {
                Storage::disk('public_site')->delete($defaultImagePath);
            }

            $defaultImagePath = $path;
        }

        $headerLogoPath = $siteDetail->header_logo_path;
        if ($request->hasFile('header_logo')) {
            $path = $request->file('header_logo')->store('site', 'public_site');
            if (is_string($headerLogoPath) && $headerLogoPath !== '' && Storage::disk('public_site')->exists($headerLogoPath)) {
                Storage::disk('public_site')->delete($headerLogoPath);
            }
            $headerLogoPath = $path;
        }

        $footerLogoPath = $siteDetail->footer_logo_path;
        if ($request->hasFile('footer_logo')) {
            $path = $request->file('footer_logo')->store('site', 'public_site');
            if (is_string($footerLogoPath) && $footerLogoPath !== '' && Storage::disk('public_site')->exists($footerLogoPath)) {
                Storage::disk('public_site')->delete($footerLogoPath);
            }
            $footerLogoPath = $path;
        }

        $normalizeHex = function (?string $v): ?string {
            if ($v === null || $v === '') {
                return null;
            }

            return preg_match('/^#[0-9A-Fa-f]{6}$/', $v) ? strtolower($v) : null;
        };

        $themePayload = [];
        if (! empty($data['reset_theme_colors'])) {
            $themePayload = [
                'theme_brand_navy' => null,
                'theme_brand_navy_mid' => null,
                'theme_brand_accent' => null,
                'theme_brand_accent_hover' => null,
                'theme_brand_topbar_muted' => null,
                'theme_footer_overlay_base' => null,
                'theme_footer_overlay_opacity' => null,
            ];
        } else {
            $overlayBase = $normalizeHex($data['theme_footer_overlay_base'] ?? null);
            $themePayload = [
                'theme_brand_navy' => $normalizeHex($data['theme_brand_navy'] ?? null),
                'theme_brand_navy_mid' => $normalizeHex($data['theme_brand_navy_mid'] ?? null),
                'theme_brand_accent' => $normalizeHex($data['theme_brand_accent'] ?? null),
                'theme_brand_accent_hover' => $normalizeHex($data['theme_brand_accent_hover'] ?? null),
                'theme_brand_topbar_muted' => $normalizeHex($data['theme_brand_topbar_muted'] ?? null),
                'theme_footer_overlay_base' => $overlayBase,
                'theme_footer_overlay_opacity' => $overlayBase !== null && isset($data['theme_footer_overlay_opacity']) && $data['theme_footer_overlay_opacity'] !== null
                    ? max(0, min(100, (int) $data['theme_footer_overlay_opacity']))
                    : null,
            ];
        }

        $siteDetail->update([
            'location' => isset($data['location']) && is_string($data['location']) ? trim($data['location']) : null,
            'map' => isset($data['map']) && is_string($data['map']) ? trim($data['map']) : null,
            'emails' => $emails,
            'phones' => $phones,
            'social_links' => $socialLinks,
            'default_image_path' => $defaultImagePath,
            'header_logo_path' => $headerLogoPath,
            'footer_logo_path' => $footerLogoPath,
            ...$themePayload,
        ]);

        return redirect()
            ->route('admin.site-details.edit')
            ->with('status', 'Site details updated.');
    }
}
