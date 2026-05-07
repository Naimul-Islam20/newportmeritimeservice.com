<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMenuRequest;
use App\Http\Requests\Admin\UpdateMenuRequest;
use App\Models\Menu;
use App\Models\SubMenu;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MenuController extends Controller
{
    private function urlTakenByAny(string $candidate, ?int $ignoreMenuId = null): bool
    {
        $menuTaken = Menu::query()
            ->when($ignoreMenuId, fn ($q) => $q->where('id', '!=', $ignoreMenuId))
            ->where('url', $candidate)
            ->exists();

        if ($menuTaken) {
            return true;
        }

        return SubMenu::query()
            ->where('url', $candidate)
            ->exists();
    }

    private function uniqueMenuPath(string $label, ?int $ignoreId = null): string
    {
        $base = '/'.Str::slug($label);
        if ($base === '/') {
            $base = '/menu';
        }

        $candidate = $base;
        $i = 2;

        while ($this->urlTakenByAny($candidate, $ignoreId)) {
            $candidate = $base.'-'.$i;
            $i++;
        }

        return $candidate;
    }

    public function index(): View
    {
        $this->authorize('viewAny', Menu::class);

        $menus = Menu::query()
            ->with(['subMenus' => fn ($q) => $q->ordered()])
            ->ordered()
            ->get();

        return view('admin.menus.index', ['menus' => $menus]);
    }

    public function create(): View
    {
        $this->authorize('create', Menu::class);

        $lastSort = (int) (Menu::query()->max('sort_order') ?? 0);
        $nextSortOrder = $lastSort + 1;

        return view('admin.menus.create', [
            'nextSortOrder' => $nextSortOrder,
        ]);
    }

    public function store(StoreMenuRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if (! array_key_exists('sort_order', $data) || $data['sort_order'] === null) {
            $data['sort_order'] = ((int) (Menu::query()->max('sort_order') ?? 0)) + 1;
        }

        $url = isset($data['url']) && is_string($data['url']) ? trim($data['url']) : '';
        if ($url !== '' && ! preg_match('#^https?://#i', $url) && ! str_starts_with($url, '/')) {
            $url = '/'.$url;
        }
        if ($url === '') {
            $data['url'] = $this->uniqueMenuPath((string) $data['label']);
        } else {
            $data['url'] = $url;
        }

        $menu = Menu::create($data);

        AuditLogger::log('admin.menu.created', $menu, [
            'label' => $menu->label,
        ], $request);

        return redirect()->route('admin.menus.index')->with('status', 'Menu created successfully.');
    }

    public function edit(Menu $menu): View
    {
        $this->authorize('update', $menu);

        return view('admin.menus.edit', [
            'menu' => $menu,
        ]);
    }

    public function update(UpdateMenuRequest $request, Menu $menu): RedirectResponse
    {
        $data = $request->validated();
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $url = isset($data['url']) && is_string($data['url']) ? trim($data['url']) : '';
        if ($url !== '' && ! preg_match('#^https?://#i', $url) && ! str_starts_with($url, '/')) {
            $url = '/'.$url;
        }
        if ($url === '') {
            $data['url'] = $this->uniqueMenuPath((string) $data['label'], $menu->id);
        } else {
            $data['url'] = $url;
        }

        $menu->fill($data);

        if (! $menu->isDirty()) {
            return redirect()->route('admin.menus.edit', $menu)->with('warning', 'No changes found. Menu was not updated.');
        }

        $menu->save();

        AuditLogger::log('admin.menu.updated', $menu, [
            'label' => $menu->label,
        ], $request);

        return redirect()->route('admin.menus.index')->with('status', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        $this->authorize('delete', $menu);

        $label = $menu->label;
        $menu->delete();

        AuditLogger::log('admin.menu.deleted', null, ['label' => $label], request());

        return redirect()->route('admin.menus.index')->with('status', 'Menu deleted successfully.');
    }
}
