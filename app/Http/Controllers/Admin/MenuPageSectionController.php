<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuPageSection;
use App\Models\SubMenu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * CRUD for morph MenuPageSection on Menu and SubMenu.
 */
class MenuPageSectionController extends Controller
{
    private function assertOwner(Menu|SubMenu $owner, MenuPageSection $section): void
    {
        abort_unless(
            $section->sectionable_type === $owner::class && (int) $section->sectionable_id === (int) $owner->id,
            404
        );
    }

    private function nextSortOrderFor(Menu|SubMenu $owner): int
    {
        $max = (int) ($owner->pageSections()->max('sort_order') ?? 0);

        return $max + 1;
    }

    /**
     * @return list<array{path: string, title: string|null}>
     */
    private function collectNewExtraGalleryFiles(Request $request, string $inputKey = 'extra_images'): array
    {
        $out = [];
        $rows = $request->input($inputKey, []);
        if (! is_array($rows)) {
            return $out;
        }

        foreach (array_keys($rows) as $i) {
            if (! $request->hasFile($inputKey.'.'.$i.'.file')) {
                continue;
            }
            $path = $request->file($inputKey.'.'.$i.'.file')->store('page-sections/images', 'public_site');
            $row = is_array($rows[$i] ?? null) ? $rows[$i] : [];
            $title = trim((string) ($row['title'] ?? ''));

            $out[] = ['path' => $path, 'title' => $title !== '' ? $title : null];
        }

        return $out;
    }

    /**
     * @return list<string>
     */
    private function normalizePoints(mixed $points): array
    {
        if (! is_array($points)) {
            return [];
        }

        return array_values(array_filter(
            array_map(fn ($v) => is_string($v) ? trim($v) : '', $points),
            fn ($v) => $v !== ''
        ));
    }

    private function normalizeImageSide(mixed $value): string
    {
        $side = strtolower(trim((string) $value));

        return in_array($side, ['right', '1', 'true', 'on'], true) ? 'right' : 'left';
    }

    private function deleteStoredPath(mixed $path): void
    {
        if (! is_string($path) || $path === '') {
            return;
        }

        foreach (['public_site', 'public'] as $disk) {
            if (Storage::disk($disk)->exists($path)) {
                Storage::disk($disk)->delete($path);
            }
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function buildSectionPayload(Request $request): array
    {
        $type = (string) $request->input('type', '');

        $rules = match ($type) {
            'two_column_image_details' => [
                'type' => ['required', 'in:two_column_image_details'],
                'layout_width' => ['required', 'in:full,short'],
                'mini_title' => ['nullable', 'string', 'max:255'],
                'title' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:5000'],
                'points' => ['nullable', 'array'],
                'points.*' => ['nullable', 'string', 'max:255'],
                'image_side' => ['required', 'in:left,right'],
                'image_file' => ['required', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:5120'],
                'is_active' => ['sometimes', 'boolean'],
                'sort_order' => ['nullable', 'integer', 'min:0'],
            ],
            'two_column_two_side_details' => [
                'type' => ['required', 'in:two_column_two_side_details'],
                'title' => ['nullable', 'string', 'max:255'],
                'left_title' => ['nullable', 'string', 'max:255'],
                'right_title' => ['nullable', 'string', 'max:255'],
                'left_description' => ['nullable', 'string', 'max:5000'],
                'right_description' => ['nullable', 'string', 'max:5000'],
                'is_active' => ['sometimes', 'boolean'],
                'sort_order' => ['nullable', 'integer', 'min:0'],
            ],
            'image' => [
                'type' => ['required', 'in:image'],
                'mini_title' => ['nullable', 'string', 'max:255'],
                'title' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:5000'],
                'image_caption' => ['nullable', 'string', 'max:255'],
                'image_file' => ['required', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:5120'],
                'extra_images' => ['nullable', 'array'],
                'extra_images.*.file' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:5120'],
                'extra_images.*.title' => ['nullable', 'string', 'max:255'],
                'is_active' => ['sometimes', 'boolean'],
                'sort_order' => ['nullable', 'integer', 'min:0'],
            ],
            'text_input' => [
                'type' => ['required', 'in:text_input'],
                'mini_title' => ['nullable', 'string', 'max:255'],
                'title' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:5000'],
                'image_file' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:5120'],
                'points' => ['nullable', 'array'],
                'points.*' => ['nullable', 'string', 'max:255'],
                'bottom_description' => ['nullable', 'string', 'max:5000'],
                'is_active' => ['sometimes', 'boolean'],
                'sort_order' => ['nullable', 'integer', 'min:0'],
            ],
            default => [
                'type' => ['required', 'in:two_column_image_details,two_column_two_side_details,image,text_input'],
            ],
        };

        $validated = $request->validate($rules);
        $resolvedType = (string) ($validated['type'] ?? $type);

        if ($resolvedType === 'two_column_image_details') {
            $data = collect($validated)->except(['type', 'title', 'is_active', 'image_file'])->all();
            $data['image_side'] = $this->normalizeImageSide($data['image_side'] ?? 'left');
            if ($request->hasFile('image_file')) {
                $data['image_path'] = $request->file('image_file')->store('page-sections/images', 'public_site');
            }
        } elseif ($resolvedType === 'image') {
            $mini = trim((string) ($validated['mini_title'] ?? ''));
            $desc = trim((string) ($validated['description'] ?? ''));
            $cap = trim((string) ($validated['image_caption'] ?? ''));
            $data = [
                'mini_title' => $mini !== '' ? $mini : null,
                'description' => $desc !== '' ? $desc : null,
                'image_caption' => $cap !== '' ? $cap : null,
                'image_path' => $request->file('image_file')->store('page-sections/images', 'public_site'),
                'extra_gallery' => $this->collectNewExtraGalleryFiles($request),
            ];
        } elseif ($resolvedType === 'two_column_two_side_details') {
            $data = collect($validated)->except(['type', 'title', 'is_active'])->all();
        } elseif ($resolvedType === 'text_input') {
            $mini = trim((string) ($validated['mini_title'] ?? ''));
            $desc = trim((string) ($validated['description'] ?? ''));
            $bottom = trim((string) ($validated['bottom_description'] ?? ''));
            $pts = $this->normalizePoints($validated['points'] ?? null);
            $data = [
                'mini_title' => $mini !== '' ? $mini : null,
                'description' => $desc !== '' ? $desc : null,
                'points' => $pts !== [] ? $pts : null,
                'bottom_description' => $bottom !== '' ? $bottom : null,
                'image_path' => $request->hasFile('image_file')
                    ? $request->file('image_file')->store('page-sections/images', 'public_site')
                    : null,
            ];
        } else {
            $data = [];
        }

        $title = trim((string) ($validated['title'] ?? ''));

        return [
            'type' => $resolvedType,
            'title' => $title !== '' ? $title : strtoupper(str_replace('_', ' ', $resolvedType)),
            'is_active' => $request->boolean('is_active'),
            'data' => $data,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function buildSectionPayloadForUpdate(Request $request, MenuPageSection $section): array
    {
        $type = (string) ($section->type ?? '');

        if ($type === 'two_column_image_details') {
            $validated = $request->validate([
                'sort_order' => ['nullable', 'integer', 'min:0'],
                'is_active' => ['sometimes', 'boolean'],
                'layout_width' => ['required', 'in:full,short'],
                'mini_title' => ['nullable', 'string', 'max:255'],
                'title' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:5000'],
                'points' => ['nullable', 'array'],
                'points.*' => ['nullable', 'string', 'max:255'],
                'image_side' => ['required', 'in:left,right'],
                'image_file' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:5120'],
            ]);

            $data = collect($validated)->except(['title', 'is_active', 'sort_order', 'image_file'])->all();
            $data['image_side'] = $this->normalizeImageSide($data['image_side'] ?? 'left');

            $current = is_array($section->data) ? $section->data : [];
            $data['image_path'] = $current['image_path'] ?? null;

            if ($request->hasFile('image_file')) {
                $path = $request->file('image_file')->store('page-sections/images', 'public_site');
                $data['image_path'] = $path;
            }

            $title = trim((string) ($validated['title'] ?? ''));

            return [
                'type' => $type,
                'title' => $title !== '' ? $title : strtoupper(str_replace('_', ' ', $type)),
                'is_active' => $request->boolean('is_active'),
                'data' => $data,
                'sort_order' => (int) ($validated['sort_order'] ?? $section->sort_order),
            ];
        }

        if ($type === 'image') {
            $validated = $request->validate([
                'sort_order' => ['nullable', 'integer', 'min:0'],
                'is_active' => ['sometimes', 'boolean'],
                'mini_title' => ['nullable', 'string', 'max:255'],
                'title' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:5000'],
                'image_caption' => ['nullable', 'string', 'max:255'],
                'image_file' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:5120'],
                'extra_images' => ['nullable', 'array'],
                'extra_images.*.file' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:5120'],
                'extra_images.*.title' => ['nullable', 'string', 'max:255'],
                'extra_remove' => ['nullable', 'array'],
                'extra_remove.*' => ['integer', 'min:0'],
                'extra_gallery_titles' => ['nullable', 'array'],
                'extra_gallery_titles.*' => ['nullable', 'string', 'max:255'],
            ]);

            $current = is_array($section->data) ? $section->data : [];
            $gallery = data_get($current, 'extra_gallery', []);
            if (! is_array($gallery)) {
                $gallery = [];
            }
            $gallery = array_values($gallery);

            $remove = array_map('intval', (array) $request->input('extra_remove', []));
            $kept = [];
            foreach ($gallery as $idx => $item) {
                if (! is_array($item)) {
                    continue;
                }
                if (in_array((int) $idx, $remove, true)) {
                    $this->deleteStoredPath(data_get($item, 'path'));

                    continue;
                }
                $path = data_get($item, 'path');
                if (! is_string($path) || $path === '') {
                    continue;
                }
                $t = $request->input('extra_gallery_titles.'.$idx, data_get($item, 'title'));
                $t = is_string($t) ? trim($t) : '';
                $kept[] = ['path' => $path, 'title' => $t !== '' ? $t : null];
            }

            $mini = trim((string) ($validated['mini_title'] ?? ''));
            $desc = trim((string) ($validated['description'] ?? ''));
            $cap = trim((string) ($validated['image_caption'] ?? ''));

            $data = [
                'mini_title' => $mini !== '' ? $mini : null,
                'description' => $desc !== '' ? $desc : null,
                'image_caption' => $cap !== '' ? $cap : null,
                'image_path' => data_get($current, 'image_path'),
                'extra_gallery' => array_merge($kept, $this->collectNewExtraGalleryFiles($request)),
            ];

            if ($request->hasFile('image_file')) {
                $data['image_path'] = $request->file('image_file')->store('page-sections/images', 'public_site');
            }

            $title = trim((string) ($validated['title'] ?? ''));

            return [
                'type' => $type,
                'title' => $title !== '' ? $title : strtoupper(str_replace('_', ' ', $type)),
                'is_active' => $request->boolean('is_active'),
                'data' => $data,
                'sort_order' => (int) ($validated['sort_order'] ?? $section->sort_order),
            ];
        }

        if ($type === 'text_input') {
            $validated = $request->validate([
                'sort_order' => ['nullable', 'integer', 'min:0'],
                'is_active' => ['sometimes', 'boolean'],
                'mini_title' => ['nullable', 'string', 'max:255'],
                'title' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:5000'],
                'image_file' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:5120'],
                'points' => ['nullable', 'array'],
                'points.*' => ['nullable', 'string', 'max:255'],
                'bottom_description' => ['nullable', 'string', 'max:5000'],
            ]);

            $current = is_array($section->data) ? $section->data : [];
            $mini = trim((string) ($validated['mini_title'] ?? ''));
            $desc = trim((string) ($validated['description'] ?? ''));
            $bottom = trim((string) ($validated['bottom_description'] ?? ''));
            $pts = $this->normalizePoints($validated['points'] ?? null);

            $data = [
                'mini_title' => $mini !== '' ? $mini : null,
                'description' => $desc !== '' ? $desc : null,
                'points' => $pts !== [] ? $pts : null,
                'bottom_description' => $bottom !== '' ? $bottom : null,
                'image_path' => data_get($current, 'image_path'),
            ];

            if ($request->hasFile('image_file')) {
                $data['image_path'] = $request->file('image_file')->store('page-sections/images', 'public_site');
            }

            $title = trim((string) ($validated['title'] ?? ''));

            return [
                'type' => $type,
                'title' => $title !== '' ? $title : strtoupper(str_replace('_', ' ', $type)),
                'is_active' => $request->boolean('is_active'),
                'data' => $data,
                'sort_order' => (int) ($validated['sort_order'] ?? $section->sort_order),
            ];
        }

        // Other types: no file uploads, reuse the common builder (forces allowed types).
        $request->merge(['type' => $type]);
        $payload = $this->buildSectionPayload($request);
        $payload['sort_order'] = $payload['sort_order'] ?: (int) $section->sort_order;

        return $payload;
    }

    private function deletePrimaryStoredImage(MenuPageSection $section): void
    {
        $this->deleteStoredPath(data_get($section->data, 'image_path'));
    }

    private function deleteSectionFiles(MenuPageSection $section): void
    {
        if ($section->type === 'two_column_image_details') {
            $this->deletePrimaryStoredImage($section);

            return;
        }

        if ($section->type === 'image') {
            $this->deletePrimaryStoredImage($section);
            $gallery = data_get($section->data, 'extra_gallery', []);
            if (is_array($gallery)) {
                foreach ($gallery as $item) {
                    $this->deleteStoredPath(data_get($item, 'path'));
                }
            }

            return;
        }

        if ($section->type === 'text_input') {
            $this->deletePrimaryStoredImage($section);
        }
    }

    public function indexMenu(Menu $menu): View
    {
        $this->authorize('update', $menu);

        return view('admin.menu-page-sections.index', [
            'pageTitle' => 'Page sections',
            'ownerLabel' => $menu->label,
            'backUrl' => route('admin.menus.index'),
            'createUrl' => route('admin.menus.page-sections.create', $menu),
            'detailsUrl' => route('admin.menus.edit', $menu),
            'sections' => $menu->pageSections()->ordered()->get(),
            'owner' => $menu,
            'editRouteName' => 'admin.menus.page-sections.edit',
            'deleteRouteName' => 'admin.menus.page-sections.destroy',
        ]);
    }

    public function createMenu(Menu $menu): View
    {
        $this->authorize('update', $menu);

        return view('admin.menu-page-sections.create', [
            'pageTitle' => 'Create section',
            'ownerLabel' => $menu->label,
            'backUrl' => route('admin.menus.page-sections.index', $menu),
            'postUrl' => route('admin.menus.page-sections.store', $menu),
        ]);
    }

    public function storeMenu(Request $request, Menu $menu): RedirectResponse
    {
        $this->authorize('update', $menu);

        $payload = $this->buildSectionPayload($request);
        $payload['sort_order'] = $payload['sort_order'] ?: $this->nextSortOrderFor($menu);

        $menu->pageSections()->create($payload);

        return redirect()
            ->route('admin.menus.page-sections.index', $menu)
            ->with('status', 'Section added.');
    }

    public function editMenu(Menu $menu, MenuPageSection $section): View
    {
        $this->authorize('update', $menu);
        $this->assertOwner($menu, $section);

        return view('admin.menu-page-sections.edit', [
            'pageTitle' => 'Edit section',
            'ownerLabel' => $menu->label,
            'backUrl' => route('admin.menus.page-sections.index', $menu),
            'updateUrl' => route('admin.menus.page-sections.update', [$menu, $section]),
            'section' => $section,
        ]);
    }

    public function updateMenu(Request $request, Menu $menu, MenuPageSection $section): RedirectResponse
    {
        $this->authorize('update', $menu);
        $this->assertOwner($menu, $section);

        $payload = $this->buildSectionPayloadForUpdate($request, $section);

        if ($section->type === 'two_column_image_details' && $request->hasFile('image_file')) {
            $this->deletePrimaryStoredImage($section);
        }
        if ($section->type === 'image' && $request->hasFile('image_file')) {
            $this->deletePrimaryStoredImage($section);
        }
        if ($section->type === 'text_input' && $request->hasFile('image_file')) {
            $this->deletePrimaryStoredImage($section);
        }

        $section->update($payload);

        return redirect()
            ->route('admin.menus.page-sections.index', $menu)
            ->with('status', 'Section updated.');
    }

    public function destroyMenu(Menu $menu, MenuPageSection $section): RedirectResponse
    {
        $this->authorize('update', $menu);
        $this->assertOwner($menu, $section);

        $this->deleteSectionFiles($section);
        $section->delete();

        return redirect()
            ->route('admin.menus.page-sections.index', $menu)
            ->with('status', 'Section deleted.');
    }

    public function indexSubMenu(SubMenu $sub_menu): View
    {
        $this->authorize('update', $sub_menu);

        return view('admin.menu-page-sections.index', [
            'pageTitle' => 'Page sections',
            'ownerLabel' => $sub_menu->label,
            'backUrl' => route('admin.sub-menus.index'),
            'createUrl' => route('admin.sub-menus.page-sections.create', $sub_menu),
            'detailsUrl' => route('admin.sub-menus.edit', $sub_menu),
            'sections' => $sub_menu->pageSections()->ordered()->get(),
            'owner' => $sub_menu,
            'editRouteName' => 'admin.sub-menus.page-sections.edit',
            'deleteRouteName' => 'admin.sub-menus.page-sections.destroy',
        ]);
    }

    public function createSubMenu(SubMenu $sub_menu): View
    {
        $this->authorize('update', $sub_menu);

        return view('admin.menu-page-sections.create', [
            'pageTitle' => 'Create section',
            'ownerLabel' => $sub_menu->label,
            'backUrl' => route('admin.sub-menus.page-sections.index', $sub_menu),
            'postUrl' => route('admin.sub-menus.page-sections.store', $sub_menu),
        ]);
    }

    public function storeSubMenu(Request $request, SubMenu $sub_menu): RedirectResponse
    {
        $this->authorize('update', $sub_menu);

        $payload = $this->buildSectionPayload($request);
        $payload['sort_order'] = $payload['sort_order'] ?: $this->nextSortOrderFor($sub_menu);

        $sub_menu->pageSections()->create($payload);

        return redirect()
            ->route('admin.sub-menus.page-sections.index', $sub_menu)
            ->with('status', 'Section added.');
    }

    public function editSubMenu(SubMenu $sub_menu, MenuPageSection $section): View
    {
        $this->authorize('update', $sub_menu);
        $this->assertOwner($sub_menu, $section);

        return view('admin.menu-page-sections.edit', [
            'pageTitle' => 'Edit section',
            'ownerLabel' => $sub_menu->label,
            'backUrl' => route('admin.sub-menus.page-sections.index', $sub_menu),
            'updateUrl' => route('admin.sub-menus.page-sections.update', [$sub_menu, $section]),
            'section' => $section,
        ]);
    }

    public function updateSubMenu(Request $request, SubMenu $sub_menu, MenuPageSection $section): RedirectResponse
    {
        $this->authorize('update', $sub_menu);
        $this->assertOwner($sub_menu, $section);

        $payload = $this->buildSectionPayloadForUpdate($request, $section);

        if ($section->type === 'two_column_image_details' && $request->hasFile('image_file')) {
            $this->deletePrimaryStoredImage($section);
        }
        if ($section->type === 'image' && $request->hasFile('image_file')) {
            $this->deletePrimaryStoredImage($section);
        }
        if ($section->type === 'text_input' && $request->hasFile('image_file')) {
            $this->deletePrimaryStoredImage($section);
        }

        $section->update($payload);

        return redirect()
            ->route('admin.sub-menus.page-sections.index', $sub_menu)
            ->with('status', 'Section updated.');
    }

    public function destroySubMenu(SubMenu $sub_menu, MenuPageSection $section): RedirectResponse
    {
        $this->authorize('update', $sub_menu);
        $this->assertOwner($sub_menu, $section);

        $this->deleteSectionFiles($section);
        $section->delete();

        return redirect()
            ->route('admin.sub-menus.page-sections.index', $sub_menu)
            ->with('status', 'Section deleted.');
    }
}
