<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMenuRequest;
use App\Http\Requests\Admin\UpdateMenuRequest;
use App\Models\Menu;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MenuController extends Controller
{
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
