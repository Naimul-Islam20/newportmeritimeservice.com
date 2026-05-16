<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAboutPageRequest;
use App\Models\AboutPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AboutPageController extends Controller
{
    public function edit(): View
    {
        $aboutPage = AboutPage::singleton();
        $this->authorize('update', $aboutPage);

        return view('admin.about-page.edit', [
            'aboutPage' => $aboutPage,
            'defaults' => AboutPage::defaultContent(),
        ]);
    }

    public function update(UpdateAboutPageRequest $request, AboutPage $about_page): RedirectResponse
    {
        $prev = [
            'hero_background' => $about_page->hero_background,
            'trust_image' => $about_page->trust_image,
            'cta_background' => $about_page->cta_background,
            'cta_video_url' => $about_page->cta_video_url,
        ];

        $data = $request->validated();
        unset(
            $data['hero_background_file'],
            $data['trust_image_file'],
            $data['cta_background_file'],
        );

        if ($request->hasFile('hero_background_file')) {
            $data['hero_background'] = $request->file('hero_background_file')->store('about-page/hero', 'public_site');
        }
        if ($request->hasFile('trust_image_file')) {
            $data['trust_image'] = $request->file('trust_image_file')->store('about-page/trust', 'public_site');
        }
        if ($request->hasFile('cta_background_file')) {
            $data['cta_background'] = $request->file('cta_background_file')->store('about-page/cta', 'public_site');
        }

        $about_page->fill($data);

        foreach (['hero_background', 'trust_image', 'cta_background', 'cta_video_url'] as $field) {
            $new = $about_page->{$field};
            $old = $prev[$field];
            if ($new !== $old && AboutPage::isManagedUploadPath($old)) {
                AboutPage::deleteManagedUpload($old);
            }
        }

        $about_page->save();

        return redirect()
            ->route('admin.about-page.edit')
            ->with('status', 'About Us page updated.');
    }
}
