<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSubMenuRequest;
use App\Http\Requests\Admin\UpdateShipSupplyLandingRequest;
use App\Http\Requests\Admin\UpdateSubMenuRequest;
use App\Models\Menu;
use App\Models\SubMenu;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ShipSupplySubMenuController extends Controller
{
    private function menuOrFail(): Menu
    {
        $menu = Menu::shipSupplyMenu();
        abort_unless($menu, 404);

        return $menu;
    }

    private function assertTopLevelShipSupplyItem(SubMenu $subMenu, Menu $menu): void
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

        return view('admin.ship-supply-sub-menus.index', [
            'menu' => $menu,
            'subMenus' => $subMenus,
        ]);
    }

    public function editLanding(): View
    {
        $menu = $this->menuOrFail();
        $this->authorize('update', $menu);

        return view('admin.ship-supply-sub-menus.landing', [
            'menu' => $menu,
        ]);
    }

    public function updateLanding(UpdateShipSupplyLandingRequest $request): RedirectResponse
    {
        $menu = $this->menuOrFail();
        $this->authorize('update', $menu);

        $previousCover = $menu->cover_image_path;

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('menus', 'public_site');
            $this->deleteCoverImage($previousCover);
            $menu->cover_image_path = $path;
        } elseif ($request->boolean('remove_cover_image')) {
            $this->deleteCoverImage($previousCover);
            $menu->cover_image_path = null;
        }

        if ($menu->isDirty('cover_image_path')) {
            $menu->save();

            AuditLogger::log('admin.menu.updated', $menu, [
                'label' => $menu->label,
                'cover_image_path' => $menu->cover_image_path,
            ], $request);
        }

        return redirect()
            ->route('admin.ship-supply-landing.edit')
            ->with('status', 'Products page background updated.');
    }

    private function deleteCoverImage(?string $path): void
    {
        if (! filled($path)) {
            return;
        }

        if (Storage::disk('public_site')->exists($path)) {
            Storage::disk('public_site')->delete($path);
        }
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function create(): View
    {
        $this->authorize('create', SubMenu::class);

        $menu = $this->menuOrFail();
        $lastSort = (int) (SubMenu::query()
            ->where('menu_id', $menu->id)
            ->whereNull('parent_sub_menu_id')
            ->max('sort_order') ?? 0);

        return view('admin.ship-supply-sub-menus.create', [
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
            ->route('admin.ship-supply-sub-menus.index')
            ->with('status', 'Sub-menu created successfully.');
    }

    public function edit(SubMenu $sub_menu): View
    {
        $menu = $this->menuOrFail();
        $this->authorize('update', $sub_menu);
        $this->assertTopLevelShipSupplyItem($sub_menu, $menu);

        return view('admin.ship-supply-sub-menus.edit', [
            'menu' => $menu,
            'subMenu' => $sub_menu,
        ]);
    }

    public function update(UpdateSubMenuRequest $request, SubMenu $sub_menu, SubMenuController $subMenuController): RedirectResponse
    {
        $menu = $this->menuOrFail();
        $this->assertTopLevelShipSupplyItem($sub_menu, $menu);
        abort_unless((int) $request->input('menu_id') === (int) $menu->id, 422);

        $subMenuController->update($request, $sub_menu);

        return redirect()
            ->route('admin.ship-supply-sub-menus.index')
            ->with('status', 'Sub-menu updated successfully.');
    }

    public function destroy(SubMenu $sub_menu): RedirectResponse
    {
        $menu = $this->menuOrFail();
        $this->authorize('delete', $sub_menu);
        $this->assertTopLevelShipSupplyItem($sub_menu, $menu);

        if ($sub_menu->children()->exists()) {
            return redirect()
                ->route('admin.ship-supply-sub-menus.index')
                ->with('warning', 'This item has nested links. Remove them first, or set the item inactive instead.');
        }

        $label = $sub_menu->label;
        $sub_menu->delete();

        AuditLogger::log('admin.sub_menu.deleted', null, ['label' => $label], request());

        return redirect()
            ->route('admin.ship-supply-sub-menus.index')
            ->with('status', 'Sub-menu deleted successfully.');
    }

    public function toggleActive(Request $request, SubMenu $sub_menu): RedirectResponse
    {
        $menu = $this->menuOrFail();
        $this->authorize('update', $sub_menu);
        $this->assertTopLevelShipSupplyItem($sub_menu, $menu);

        $sub_menu->update([
            'is_active' => ! $sub_menu->is_active,
        ]);

        AuditLogger::log('admin.sub_menu.updated', $sub_menu, [
            'label' => $sub_menu->label,
            'is_active' => $sub_menu->is_active,
        ], $request);

        $state = $sub_menu->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->route('admin.ship-supply-sub-menus.index')
            ->with('status', "\"{$sub_menu->label}\" {$state} on the site menu.");
    }
}
