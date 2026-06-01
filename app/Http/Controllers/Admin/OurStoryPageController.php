<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOurStoryPageRequest;
use App\Models\OurStoryPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OurStoryPageController extends Controller
{
    public function edit(): View
    {
        $page = OurStoryPage::singleton();
        $this->authorize('update', $page);

        return view('admin.our-story-page.edit', [
            'page' => $page,
        ]);
    }

    public function update(UpdateOurStoryPageRequest $request, OurStoryPage $our_story_page): RedirectResponse
    {
        $prevHero = $our_story_page->hero_background;
        $prevMilestonePaths = collect(is_array($our_story_page->milestones) ? $our_story_page->milestones : [])
            ->pluck('image_path')
            ->filter(fn ($p) => is_string($p) && $p !== '')
            ->all();

        $data = $request->validated();
        unset($data['hero_background_file'], $data['remove_hero_background']);
        unset(
            $data['milestone_year'],
            $data['milestone_title'],
            $data['milestone_text'],
            $data['milestone_image_path'],
            $data['milestone_image'],
        );

        if ($request->hasFile('hero_background_file')) {
            $data['hero_background'] = $request->file('hero_background_file')
                ->store(OurStoryPage::uploadPrefix().'/hero', 'public_site');
        } elseif ($request->boolean('remove_hero_background')) {
            $data['hero_background'] = null;
        }

        $data['intro_paragraphs'] = $this->filterStrings($request->input('intro_paragraphs', []));
        $data['milestones'] = $this->buildMilestones($request);

        $our_story_page->fill($data);
        $our_story_page->save();

        if ($our_story_page->hero_background !== $prevHero) {
            OurStoryPage::deleteManagedUpload($prevHero);
        }

        $newPaths = collect($data['milestones'])->pluck('image_path')->filter()->all();
        foreach ($prevMilestonePaths as $old) {
            if (! in_array($old, $newPaths, true) && OurStoryPage::isManagedUploadPath($old, OurStoryPage::uploadPrefix())) {
                OurStoryPage::deleteManagedUpload($old);
            }
        }

        return redirect()
            ->route('admin.our-story-page.edit')
            ->with('status', 'Our Story page updated.');
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

    /**
     * @return list<array{year: string, title: string, text: string, image_path: ?string}>
     */
    private function buildMilestones(Request $request): array
    {
        $years = $request->input('milestone_year', []);
        if (! is_array($years)) {
            return [];
        }

        $milestones = [];
        foreach (array_keys($years) as $i) {
            $year = trim((string) ($request->input("milestone_year.{$i}") ?? ''));
            $title = trim((string) ($request->input("milestone_title.{$i}") ?? ''));
            $text = trim((string) ($request->input("milestone_text.{$i}") ?? ''));
            if ($year === '' && $title === '' && $text === '') {
                continue;
            }

            $imagePath = trim((string) ($request->input("milestone_image_path.{$i}") ?? ''));
            if ($request->hasFile("milestone_image.{$i}")) {
                $imagePath = $request->file("milestone_image.{$i}")
                    ->store(OurStoryPage::uploadPrefix().'/milestones', 'public_site');
            }

            $milestones[] = [
                'year' => $year,
                'title' => $title,
                'text' => $text,
                'image_path' => $imagePath !== '' ? $imagePath : null,
            ];
        }

        return $milestones;
    }
}
