<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSubMenuRequest;
use App\Http\Requests\Admin\UpdateSubMenuRequest;
use App\Models\Menu;
use App\Models\SubMenu;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubMenuController extends Controller
{
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

        $subMenu = SubMenu::create($data);

        AuditLogger::log('admin.sub_menu.created', $subMenu, [
            'label' => $subMenu->label,
            'menu_id' => $subMenu->menu_id,
        ], $request);

        return redirect()->route('admin.sub-menus.index')->with('status', 'Sub-menu created successfully.');
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

        $sub_menu->fill($data);

        if (! $sub_menu->isDirty()) {
            return redirect()->route('admin.sub-menus.edit', $sub_menu)->with('warning', 'No changes found. Sub-menu was not updated.');
        }

        $sub_menu->save();

        AuditLogger::log('admin.sub_menu.updated', $sub_menu, [
            'label' => $sub_menu->label,
            'menu_id' => $sub_menu->menu_id,
        ], $request);

        return redirect()->route('admin.sub-menus.index')->with('status', 'Sub-menu updated successfully.');
    }

    public function destroy(SubMenu $sub_menu): RedirectResponse
    {
        $this->authorize('delete', $sub_menu);

        $label = $sub_menu->label;
        $sub_menu->delete();

        AuditLogger::log('admin.sub_menu.deleted', null, ['label' => $label], request());

        return redirect()->route('admin.sub-menus.index')->with('status', 'Sub-menu deleted successfully.');
    }
}
