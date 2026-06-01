<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateCeoMessagePageRequest;
use App\Models\CeoMessagePage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CeoMessagePageController extends Controller
{
    public function edit(): View
    {
        $page = CeoMessagePage::singleton();
        $this->authorize('update', $page);

        return view('admin.ceo-message-page.edit', [
            'page' => $page,
        ]);
    }

    public function update(UpdateCeoMessagePageRequest $request, CeoMessagePage $ceo_message_page): RedirectResponse
    {
        $prevHero = $ceo_message_page->hero_background;
        $prevPortrait = $ceo_message_page->portrait_path;

        $data = $request->validated();
        unset(
            $data['hero_background_file'],
            $data['portrait_file'],
            $data['remove_hero_background'],
            $data['remove_portrait'],
        );

        if ($request->hasFile('hero_background_file')) {
            $data['hero_background'] = $request->file('hero_background_file')
                ->store(CeoMessagePage::uploadPrefix().'/hero', 'public_site');
        } elseif ($request->boolean('remove_hero_background')) {
            $data['hero_background'] = null;
        }

        if ($request->hasFile('portrait_file')) {
            $data['portrait_path'] = $request->file('portrait_file')
                ->store(CeoMessagePage::uploadPrefix().'/portrait', 'public_site');
        } elseif ($request->boolean('remove_portrait')) {
            $data['portrait_path'] = null;
        }

        $paragraphs = $request->input('paragraphs', []);
        $data['paragraphs'] = is_array($paragraphs)
            ? array_values(array_filter(array_map(
                fn ($v) => is_string($v) ? trim($v) : '',
                $paragraphs,
            ), fn ($v) => $v !== ''))
            : [];

        $ceo_message_page->fill($data);
        $ceo_message_page->save();

        if ($ceo_message_page->hero_background !== $prevHero) {
            CeoMessagePage::deleteManagedUpload($prevHero);
        }
        if ($ceo_message_page->portrait_path !== $prevPortrait) {
            CeoMessagePage::deleteManagedUpload($prevPortrait);
        }

        return redirect()
            ->route('admin.ceo-message-page.edit')
            ->with('status', 'Message from the CEO page updated.');
    }
}
