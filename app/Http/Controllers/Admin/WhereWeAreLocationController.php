<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreWhereWeAreLocationRequest;
use App\Http\Requests\Admin\UpdateWhereWeAreLocationRequest;
use App\Models\Menu;
use App\Models\SubMenu;
use App\Models\WhereWeAreLocation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WhereWeAreLocationController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', WhereWeAreLocation::class);

        $locations = WhereWeAreLocation::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.where-we-are-locations.index', [
            'locations' => $locations,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', WhereWeAreLocation::class);

        return view('admin.where-we-are-locations.create', [
            'location' => new WhereWeAreLocation(['is_active' => true, 'show_quality_block' => true]),
        ]);
    }

    public function store(StoreWhereWeAreLocationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        unset($data['hero_background_file'], $data['remove_hero_background'], $data['brochure_file_upload'], $data['remove_brochure_file']);
        $data['body_paragraphs'] = $this->filterStrings($request->input('body_paragraphs', []));
        $data['sidebar_extras'] = $this->filterSidebarExtras($request->input('sidebar_extras', []));
        $data['slug'] = Str::slug($data['slug'] ?: $data['hero_title']);

        $location = WhereWeAreLocation::query()->create($data);
        $this->handleUploads($request, $location);
        $this->syncNav($location);

        return redirect()
            ->route('admin.where-we-are-locations.edit', $location)
            ->with('status', 'Location created.');
    }

    public function edit(WhereWeAreLocation $where_we_are_location): View
    {
        $this->authorize('update', $where_we_are_location);

        return view('admin.where-we-are-locations.edit', [
            'location' => $where_we_are_location,
        ]);
    }

    public function update(UpdateWhereWeAreLocationRequest $request, WhereWeAreLocation $where_we_are_location): RedirectResponse
    {
        $prevHero = $where_we_are_location->hero_background;
        $prevBrochure = $where_we_are_location->brochure_file;

        $data = $request->validated();
        unset($data['hero_background_file'], $data['remove_hero_background'], $data['brochure_file_upload'], $data['remove_brochure_file']);
        $data['body_paragraphs'] = $this->filterStrings($request->input('body_paragraphs', []));
        $data['sidebar_extras'] = $this->filterSidebarExtras($request->input('sidebar_extras', []));
        if (filled($data['slug'] ?? null)) {
            $data['slug'] = Str::slug($data['slug']);
        }

        $where_we_are_location->fill($data);
        $where_we_are_location->save();
        $this->handleUploads($request, $where_we_are_location);
        $this->syncNav($where_we_are_location);

        if ($where_we_are_location->hero_background !== $prevHero) {
            WhereWeAreLocation::deleteManagedUpload($prevHero);
        }
        if ($where_we_are_location->brochure_file !== $prevBrochure) {
            WhereWeAreLocation::deleteManagedUpload($prevBrochure);
        }

        return redirect()
            ->route('admin.where-we-are-locations.edit', $where_we_are_location)
            ->with('status', 'Location updated.');
    }

    public function destroy(WhereWeAreLocation $where_we_are_location): RedirectResponse
    {
        $this->authorize('delete', $where_we_are_location);

        $title = $where_we_are_location->hero_title;
        $subMenu = $where_we_are_location->subMenu;

        $this->purgeUploads($where_we_are_location);
        $where_we_are_location->delete();

        if ($subMenu) {
            $subMenu->delete();
        }

        return redirect()
            ->route('admin.where-we-are-locations.index')
            ->with('status', "Location “{$title}” deleted.");
    }

    private function purgeUploads(WhereWeAreLocation $location): void
    {
        WhereWeAreLocation::deleteManagedUpload($location->hero_background);
        WhereWeAreLocation::deleteManagedUpload($location->brochure_file);

        if (! is_array($location->gallery_images)) {
            return;
        }

        foreach ($location->gallery_images as $image) {
            if (is_string($image)) {
                WhereWeAreLocation::deleteManagedUpload($image);
            }
        }
    }

    private function syncNav(WhereWeAreLocation $location): void
    {
        [$menu, $parent] = $this->resolveWhereWeAreSubMenu();

        if ($menu && $parent) {
            $location->syncSubMenu($menu, $parent);
        }
    }

    /**
     * @return array{0: ?Menu, 1: ?SubMenu}
     */
    private function resolveWhereWeAreSubMenu(): array
    {
        $menu = Menu::query()
            ->whereRaw('LOWER(label) LIKE ?', ['%who we are%'])
            ->first();

        if (! $menu) {
            return [null, null];
        }

        $parent = SubMenu::query()
            ->where('menu_id', $menu->id)
            ->whereNull('parent_sub_menu_id')
            ->where(function ($q): void {
                $q->where('url', '/where-we-are')
                    ->orWhere('url', 'where-we-are')
                    ->orWhereRaw('LOWER(label) LIKE ?', ['%where we are%']);
            })
            ->first();

        return [$menu, $parent];
    }

    private function handleUploads(UpdateWhereWeAreLocationRequest|StoreWhereWeAreLocationRequest $request, WhereWeAreLocation $location): void
    {
        if ($request->hasFile('hero_background_file')) {
            $location->hero_background = $request->file('hero_background_file')
                ->store(WhereWeAreLocation::uploadPrefix().'/hero', 'public_site');
            $location->save();
        } elseif ($request->boolean('remove_hero_background')) {
            $location->hero_background = null;
            $location->save();
        }

        if ($request->hasFile('brochure_file_upload')) {
            $location->brochure_file = $request->file('brochure_file_upload')
                ->store(WhereWeAreLocation::uploadPrefix().'/brochure', 'public_site');
            $location->save();
        } elseif ($request->boolean('remove_brochure_file')) {
            $location->brochure_file = null;
            $location->save();
        }
    }

    /**
     * @return list<array{label: string, url: string}>
     */
    private function filterSidebarExtras(mixed $items): array
    {
        if (! is_array($items)) {
            return [];
        }

        $out = [];
        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }
            $label = trim((string) ($item['label'] ?? ''));
            $url = trim((string) ($item['url'] ?? ''));
            if ($label !== '' && $url !== '') {
                $out[] = ['label' => $label, 'url' => $url];
            }
        }

        return $out;
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
