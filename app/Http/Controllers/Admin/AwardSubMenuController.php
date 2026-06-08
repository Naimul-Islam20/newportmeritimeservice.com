<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSubMenuRequest;
use App\Http\Requests\Admin\UpdateSubMenuRequest;
use App\Models\Menu;
use App\Models\SubMenu;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AwardSubMenuController extends Controller
{
    private function menuOrFail(): Menu
    {
        $menu = Menu::awardMenu();
        abort_unless($menu, 404);

        return $menu;
    }

    private function assertTopLevelAwardItem(SubMenu $subMenu, Menu $menu): void
    {
        abort_unless((int) $subMenu->menu_id === (int) $menu->id, 404);
        abort_unless($subMenu->parent_sub_menu_id === null, 404);
    }

    public function index(): View
    {
        $this->authorize('viewAny', SubMenu::class);

        $menu = $this->menuOrFail();

        $subMenus = SubMenu::query()
            ->where('menu_id', $menu->id)
            ->whereNull('parent_sub_menu_id')
            ->ordered()
            ->get();

        return view('admin.award-sub-menus.index', [
            'menu' => $menu,
            'subMenus' => $subMenus,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', SubMenu::class);

        $menu = $this->menuOrFail();
        $lastSort = (int) (SubMenu::query()
            ->where('menu_id', $menu->id)
            ->whereNull('parent_sub_menu_id')
            ->max('sort_order') ?? 0);

        return view('admin.award-sub-menus.create', [
            'menu' => $menu,
            'nextSortOrder' => $lastSort + 1,
        ]);
    }

    public function store(StoreSubMenuRequest $request, SubMenuController $subMenuController): RedirectResponse
    {
        $menu = $this->menuOrFail();
        abort_unless((int) $request->input('menu_id') === (int) $menu->id, 422);

        $subMenuController->store($request);

        return redirect()
            ->route('admin.award-sub-menus.index')
            ->with('status', 'Sub-menu created successfully.');
    }

    public function edit(SubMenu $sub_menu): View
    {
        $menu = $this->menuOrFail();
        $this->authorize('update', $sub_menu);
        $this->assertTopLevelAwardItem($sub_menu, $menu);

        return view('admin.award-sub-menus.edit', [
            'menu' => $menu,
            'subMenu' => $sub_menu,
        ]);
    }

    public function update(UpdateSubMenuRequest $request, SubMenu $sub_menu, SubMenuController $subMenuController): RedirectResponse
    {
        $menu = $this->menuOrFail();
        $this->assertTopLevelAwardItem($sub_menu, $menu);
        abort_unless((int) $request->input('menu_id') === (int) $menu->id, 422);

        $subMenuController->update($request, $sub_menu);

        return redirect()
            ->route('admin.award-sub-menus.index')
            ->with('status', 'Sub-menu updated successfully.');
    }

    public function destroy(SubMenu $sub_menu): RedirectResponse
    {
        $menu = $this->menuOrFail();
        $this->authorize('delete', $sub_menu);
        $this->assertTopLevelAwardItem($sub_menu, $menu);

        if ($sub_menu->children()->exists()) {
            return redirect()
                ->route('admin.award-sub-menus.index')
                ->with('warning', 'This item has nested links. Remove them first, or set the item inactive instead.');
        }

        $label = $sub_menu->label;
        $sub_menu->delete();

        AuditLogger::log('admin.sub_menu.deleted', null, ['label' => $label], request());

        return redirect()
            ->route('admin.award-sub-menus.index')
            ->with('status', 'Sub-menu deleted successfully.');
    }

    public function toggleActive(Request $request, SubMenu $sub_menu): RedirectResponse
    {
        $menu = $this->menuOrFail();
        $this->authorize('update', $sub_menu);
        $this->assertTopLevelAwardItem($sub_menu, $menu);

        $sub_menu->update([
            'is_active' => ! $sub_menu->is_active,
        ]);

        AuditLogger::log('admin.sub_menu.updated', $sub_menu, [
            'label' => $sub_menu->label,
            'is_active' => $sub_menu->is_active,
        ], $request);

        $state = $sub_menu->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->route('admin.award-sub-menus.index')
            ->with('status', "\"{$sub_menu->label}\" {$state} on the site menu.");
    }
}
