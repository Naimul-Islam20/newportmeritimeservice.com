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

        $siteDetail->update([
            'location' => isset($data['location']) && is_string($data['location']) ? trim($data['location']) : null,
            'map' => isset($data['map']) && is_string($data['map']) ? trim($data['map']) : null,
            'emails' => $emails,
            'phones' => $phones,
            'social_links' => $socialLinks,
            'default_image_path' => $defaultImagePath,
        ]);

        return redirect()
            ->route('admin.site-details.edit')
            ->with('status', 'Site details updated.');
    }
}
