<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOurTeamPageRequest;
use App\Models\OurTeamPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OurTeamPageController extends Controller
{
    public function edit(): View
    {
        $page = OurTeamPage::singleton();
        $this->authorize('update', $page);

        return view('admin.our-team-page.edit', [
            'page' => $page,
        ]);
    }

    public function update(UpdateOurTeamPageRequest $request, OurTeamPage $our_team_page): RedirectResponse
    {
        $prevHero = $our_team_page->hero_background;
        $prevPhotos = $this->collectMemberPhotoPaths($our_team_page->team_sections);

        $data = $request->validated();
        unset($data['hero_background_file'], $data['remove_hero_background']);
        unset(
            $data['regional_label'],
            $data['regional_url'],
            $data['category_label'],
            $data['category_url'],
            $data['section_heading'],
            $data['member_name'],
            $data['member_role'],
            $data['member_email'],
            $data['member_phone'],
            $data['member_photo_path'],
            $data['member_photo'],
        );

        if ($request->hasFile('hero_background_file')) {
            $data['hero_background'] = $request->file('hero_background_file')
                ->store(OurTeamPage::uploadPrefix().'/hero', 'public_site');
        } elseif ($request->boolean('remove_hero_background')) {
            $data['hero_background'] = null;
        }

        $data['regional_nav'] = $this->buildNavPairs(
            $request->input('regional_label', []),
            $request->input('regional_url', []),
        );
        $data['category_nav'] = $this->buildNavPairs(
            $request->input('category_label', []),
            $request->input('category_url', []),
        );
        $data['team_sections'] = $this->buildTeamSections($request);

        $our_team_page->fill($data);
        $our_team_page->save();

        if ($our_team_page->hero_background !== $prevHero) {
            OurTeamPage::deleteManagedUpload($prevHero);
        }

        $newPhotos = $this->collectMemberPhotoPaths($data['team_sections']);
        foreach ($prevPhotos as $old) {
            if (! in_array($old, $newPhotos, true) && OurTeamPage::isManagedUploadPath($old, OurTeamPage::uploadPrefix())) {
                OurTeamPage::deleteManagedUpload($old);
            }
        }

        return redirect()
            ->route('admin.our-team-page.edit')
            ->with('status', 'Our Team page updated.');
    }

    /**
     * @return list<array{label: string, url: string}>
     */
    private function buildNavPairs(mixed $labels, mixed $urls): array
    {
        if (! is_array($labels)) {
            return [];
        }

        $out = [];
        foreach (array_keys($labels) as $i) {
            $label = trim((string) ($labels[$i] ?? ''));
            if ($label === '') {
                continue;
            }
            $url = is_array($urls) ? trim((string) ($urls[$i] ?? '#')) : '#';
            $out[] = ['label' => $label, 'url' => $url !== '' ? $url : '#'];
        }

        return $out;
    }

    /**
     * @return list<array{heading: string, members: list<array{name: string, role: string, email: string, phone: ?string, photo_path: ?string}>}>
     */
    private function buildTeamSections(Request $request): array
    {
        $headings = $request->input('section_heading', []);
        if (! is_array($headings)) {
            return [];
        }

        $sections = [];
        foreach (array_keys($headings) as $si) {
            $heading = trim((string) ($request->input("section_heading.{$si}") ?? ''));
            $names = $request->input("member_name.{$si}", []);
            if (! is_array($names)) {
                $names = [];
            }

            $members = [];
            foreach (array_keys($names) as $mi) {
                $name = trim((string) ($request->input("member_name.{$si}.{$mi}") ?? ''));
                if ($name === '') {
                    continue;
                }
                $photoPath = trim((string) ($request->input("member_photo_path.{$si}.{$mi}") ?? ''));
                if ($request->hasFile("member_photo.{$si}.{$mi}")) {
                    $photoPath = $request->file("member_photo.{$si}.{$mi}")
                        ->store(OurTeamPage::uploadPrefix().'/members', 'public_site');
                }

                $phone = trim((string) ($request->input("member_phone.{$si}.{$mi}") ?? ''));

                $members[] = [
                    'name' => $name,
                    'role' => trim((string) ($request->input("member_role.{$si}.{$mi}") ?? '')),
                    'email' => trim((string) ($request->input("member_email.{$si}.{$mi}") ?? '')),
                    'phone' => $phone !== '' ? $phone : null,
                    'photo_path' => $photoPath !== '' ? $photoPath : null,
                ];
            }

            if ($heading === '' && $members === []) {
                continue;
            }

            $sections[] = [
                'heading' => $heading,
                'members' => $members,
            ];
        }

        return $sections;
    }

    /**
     * @return list<string>
     */
    private function collectMemberPhotoPaths(mixed $sections): array
    {
        if (! is_array($sections)) {
            return [];
        }

        $paths = [];
        foreach ($sections as $section) {
            if (! is_array($section)) {
                continue;
            }
            foreach ($section['members'] ?? [] as $member) {
                if (is_array($member) && is_string($member['photo_path'] ?? null) && $member['photo_path'] !== '') {
                    $paths[] = $member['photo_path'];
                }
            }
        }

        return $paths;
    }
}
