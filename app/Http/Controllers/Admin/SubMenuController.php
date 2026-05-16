<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSubMenuRequest;
use App\Http\Requests\Admin\UpdateSubMenuRequest;
use App\Models\Menu;
use App\Models\SubMenu;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SubMenuController extends Controller
{
    private function urlTakenByAny(string $candidate, ?int $ignoreSubMenuId = null): bool
    {
        $subTaken = SubMenu::query()
            ->when($ignoreSubMenuId, fn ($q) => $q->where('id', '!=', $ignoreSubMenuId))
            ->where('url', $candidate)
            ->exists();

        if ($subTaken) {
            return true;
        }

        return Menu::query()
            ->where('url', $candidate)
            ->exists();
    }

    private function uniqueSubMenuPath(Menu $menu, string $label, ?int $ignoreId = null): string
    {
        $subPart = Str::slug($label);

        if ($subPart === '') {
            $subPart = 'item';
        }

        $menuBase = $menu->normalizedPath() ?: '/'.(Str::slug((string) $menu->label) ?: 'menu');
        $menuBase = '/'.ltrim($menuBase, '/');

        $base = rtrim($menuBase, '/').'/'.$subPart;
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
        $this->authorize('viewAny', SubMenu::class);

        $subMenus = SubMenu::query()
            ->with('menu')
            ->ordered()
            ->latest('id')
            ->paginate(50)
            ->withQueryString();

        return view('admin.sub-menus.index', ['subMenus' => $subMenus]);
    }

    public function create(): View
    {
        $this->authorize('create', SubMenu::class);

        $menus = Menu::query()->ordered()->get();
        $lastSort = (int) (SubMenu::query()->max('sort_order') ?? 0);
        $nextSortOrder = $lastSort + 1;

        return view('admin.sub-menus.create', [
            'menus' => $menus,
            'nextSortOrder' => $nextSortOrder,
        ]);
    }

    public function store(StoreSubMenuRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if (! array_key_exists('sort_order', $data) || $data['sort_order'] === null) {
            $data['sort_order'] = ((int) (SubMenu::query()->max('sort_order') ?? 0)) + 1;
        }

        $menu = Menu::query()->findOrFail((int) $data['menu_id']);
        $url = isset($data['url']) && is_string($data['url']) ? trim($data['url']) : '';
        if ($url !== '' && ! preg_match('#^https?://#i', $url) && ! str_starts_with($url, '/')) {
            $url = '/'.$url;
        }
        if ($url === '') {
            $data['url'] = $this->uniqueSubMenuPath($menu, (string) $data['label']);
        } else {
            $data['url'] = $url;
        }

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('sub-menus', 'public_site');
        }

        unset($data['cover_image']);
        $data['cover_image_path'] = $coverPath;

        $subMenu = SubMenu::create($data);

        AuditLogger::log('admin.sub_menu.created', $subMenu, [
            'label' => $subMenu->label,
            'menu_id' => $subMenu->menu_id,
        ], $request);

        return redirect()
            ->route('admin.sub-menus.page-sections.index', $subMenu)
            ->with('status', 'Sub-menu created successfully.');
    }

    public function edit(SubMenu $sub_menu): View
    {
        $this->authorize('update', $sub_menu);

        $menus = Menu::query()->ordered()->get();

        return view('admin.sub-menus.edit', [
            'subMenu' => $sub_menu,
            'menus' => $menus,
        ]);
    }

    public function update(UpdateSubMenuRequest $request, SubMenu $sub_menu): RedirectResponse
    {
        $data = $request->validated();
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $menu = Menu::query()->findOrFail((int) $data['menu_id']);
        $url = isset($data['url']) && is_string($data['url']) ? trim($data['url']) : '';
        if ($url !== '' && ! preg_match('#^https?://#i', $url) && ! str_starts_with($url, '/')) {
            $url = '/'.$url;
        }
        if ($url === '') {
            $data['url'] = $this->uniqueSubMenuPath($menu, (string) $data['label'], $sub_menu->id);
        } else {
            $data['url'] = $url;
        }

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('sub-menus', 'public_site');

            if ($sub_menu->cover_image_path && Storage::disk('public_site')->exists($sub_menu->cover_image_path)) {
                Storage::disk('public_site')->delete($sub_menu->cover_image_path);
            }
            if ($sub_menu->cover_image_path && Storage::disk('public')->exists($sub_menu->cover_image_path)) {
                Storage::disk('public')->delete($sub_menu->cover_image_path);
            }

            $data['cover_image_path'] = $path;
        }

        unset($data['cover_image']);
        $sub_menu->fill($data);

        if (! $sub_menu->isDirty()) {
            return redirect()->route('admin.sub-menus.page-sections.index', $sub_menu)->with('warning', 'No changes found. Sub-menu was not updated.');
        }

        $sub_menu->save();

        AuditLogger::log('admin.sub_menu.updated', $sub_menu, [
            'label' => $sub_menu->label,
            'menu_id' => $sub_menu->menu_id,
        ], $request);

        return redirect()
            ->route('admin.sub-menus.page-sections.index', $sub_menu)
            ->with('status', 'Sub-menu updated successfully.');
    }

    public function destroy(SubMenu $sub_menu): RedirectResponse
    {
        $this->authorize('delete', $sub_menu);

        $label = $sub_menu->label;

        if ($sub_menu->cover_image_path && Storage::disk('public_site')->exists($sub_menu->cover_image_path)) {
            Storage::disk('public_site')->delete($sub_menu->cover_image_path);
        }
        if ($sub_menu->cover_image_path && Storage::disk('public')->exists($sub_menu->cover_image_path)) {
            Storage::disk('public')->delete($sub_menu->cover_image_path);
        }

        $sub_menu->delete();

        AuditLogger::log('admin.sub_menu.deleted', null, ['label' => $label], request());

        return redirect()->route('admin.sub-menus.index')->with('status', 'Sub-menu deleted successfully.');
    }
}
