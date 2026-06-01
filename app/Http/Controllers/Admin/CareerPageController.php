<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateCareerPageRequest;
use App\Models\CareerPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CareerPageController extends Controller
{
    public function edit(): View
    {
        $page = CareerPage::singleton();
        $this->authorize('update', $page);

        return view('admin.career-page.edit', [
            'page' => $page,
        ]);
    }

    public function update(UpdateCareerPageRequest $request, CareerPage $career_page): RedirectResponse
    {
        $prevHero = $career_page->hero_background;
        $prevAside = $career_page->aside_image;

        $data = $request->validated();
        unset($data['hero_background_file'], $data['remove_hero_background']);
        unset($data['aside_image_file'], $data['remove_aside_image']);

        if ($request->hasFile('hero_background_file')) {
            $data['hero_background'] = $request->file('hero_background_file')
                ->store(CareerPage::uploadPrefix().'/hero', 'public_site');
        } elseif ($request->boolean('remove_hero_background')) {
            $data['hero_background'] = null;
        }

        if ($request->hasFile('aside_image_file')) {
            $data['aside_image'] = $request->file('aside_image_file')
                ->store(CareerPage::uploadPrefix().'/aside', 'public_site');
        } elseif ($request->boolean('remove_aside_image')) {
            $data['aside_image'] = null;
        }

        $data['intro_paragraphs'] = $this->filterStrings($request->input('intro_paragraphs', []));
        $data['qualifications'] = $this->filterStrings($request->input('qualifications', []));
        $data['offers_paragraphs'] = $this->filterStrings($request->input('offers_paragraphs', []));

        $career_page->fill($data);
        $career_page->save();

        if ($career_page->hero_background !== $prevHero) {
            CareerPage::deleteManagedUpload($prevHero);
        }
        if ($career_page->aside_image !== $prevAside) {
            CareerPage::deleteManagedUpload($prevAside);
        }

        return redirect()
            ->route('admin.career-page.edit')
            ->with('status', 'Career page updated.');
    }

    /**
     * @return list<string>
     */
    private function filterStrings(mixed $items): array
    {
        if (! is_array($items)) {
            return [];
        }

        return array_values(array_filter(array_map(
            fn ($v) => is_string($v) ? trim($v) : '',
            $items,
        ), fn ($v) => $v !== ''));
    }
}
