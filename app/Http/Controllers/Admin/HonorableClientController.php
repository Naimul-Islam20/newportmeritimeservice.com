<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HonorableClient;
use App\Models\HonorableClientPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HonorableClientController extends Controller
{
    public function index(): View
    {
        $page = HonorableClientPage::singleton();
        $this->authorize('viewAny', $page);

        $clients = HonorableClient::query()->ordered()->get();
        $nextSort = ((int) ($clients->max('sort_order') ?? 0)) + 1;

        return view('admin.honorable-clients.index', [
            'page' => $page,
            'clients' => $clients,
            'nextSort' => $nextSort,
        ]);
    }

    public function updatePage(Request $request): RedirectResponse
    {
        $page = HonorableClientPage::singleton();
        $this->authorize('update', $page);

        $data = $request->validate([
            'hero_title' => ['required', 'string', 'max:200'],
            'page_intro' => ['nullable', 'string', 'max:2000'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
            'hero_background_file' => ['nullable', 'image', 'max:5120'],
            'remove_hero_background' => ['nullable', 'boolean'],
        ]);

        $prevHero = $page->hero_background;

        if ($request->hasFile('hero_background_file')) {
            $data['hero_background'] = $request->file('hero_background_file')
                ->store('honorable-clients/hero', 'public_site');
        } elseif ($request->boolean('remove_hero_background')) {
            $data['hero_background'] = null;
        }

        unset($data['hero_background_file'], $data['remove_hero_background']);

        $page->fill([
            'hero_title' => $data['hero_title'],
            'page_intro' => $data['page_intro'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'hero_background' => $data['hero_background'] ?? $page->hero_background,
        ]);

        if ($page->hero_background !== $prevHero) {
            HonorableClientPage::deleteManagedUpload($prevHero);
        }

        $page->save();

        return redirect()
            ->route('admin.honorable-clients.index')
            ->with('status', 'Page settings saved.');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', HonorableClient::class);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'logo_file' => ['nullable', 'image', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $client = HonorableClient::query()->create([
            'name' => $data['name'],
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->hasFile('logo_file')) {
            $client->logo_path = $request->file('logo_file')
                ->store('honorable-clients/logos', 'public_site');
            $client->save();
        }

        return redirect()
            ->route('admin.honorable-clients.index')
            ->with('status', 'Client added.');
    }

    public function edit(HonorableClient $honorable_client): View
    {
        $this->authorize('update', $honorable_client);

        return view('admin.honorable-clients.edit', [
            'client' => $honorable_client,
            'page' => HonorableClientPage::singleton(),
        ]);
    }

    public function update(Request $request, HonorableClient $honorable_client): RedirectResponse
    {
        $this->authorize('update', $honorable_client);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'logo_file' => ['nullable', 'image', 'max:4096'],
            'remove_logo' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $prevLogo = $honorable_client->logo_path;

        $honorable_client->fill([
            'name' => $data['name'],
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($request->hasFile('logo_file')) {
            $honorable_client->logo_path = $request->file('logo_file')
                ->store('honorable-clients/logos', 'public_site');
        } elseif ($request->boolean('remove_logo')) {
            $honorable_client->logo_path = null;
        }

        $honorable_client->save();

        if ($honorable_client->logo_path !== $prevLogo) {
            HonorableClient::deleteManagedUpload($prevLogo);
        }

        return redirect()
            ->route('admin.honorable-clients.index')
            ->with('status', 'Client updated.');
    }

    public function destroy(HonorableClient $honorable_client): RedirectResponse
    {
        $this->authorize('delete', $honorable_client);

        $name = $honorable_client->name;
        HonorableClient::deleteManagedUpload($honorable_client->logo_path);
        $honorable_client->delete();

        return redirect()
            ->route('admin.honorable-clients.index')
            ->with('status', "“{$name}” removed.");
    }
}
