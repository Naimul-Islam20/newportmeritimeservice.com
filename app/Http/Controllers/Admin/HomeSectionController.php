<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateHomeServiceAreaSettingRequest;
use App\Models\HomeSection;
use App\Models\HomeServiceAreaSetting;
use App\Models\HomeVisualFramesSetting;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class HomeSectionController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', HomeSection::class);

        return view('admin.home-sections.index', [
            'sections' => HomeSection::query()->ordered()->get(),
        ]);
    }

    public function serviceArea(): View
    {
        $this->authorize('viewAny', HomeServiceAreaSetting::class);

        $setting = HomeServiceAreaSetting::query()->first();
        if (! $setting) {
            $setting = new HomeServiceAreaSetting(HomeServiceAreaSetting::defaultAttributes());
        }

        return view('admin.home-sections.service-area', [
            'setting' => $setting,
        ]);
    }

    public function updateServiceArea(UpdateHomeServiceAreaSettingRequest $request): RedirectResponse
    {
        $setting = HomeServiceAreaSetting::query()->first();
        if (! $setting) {
            $setting = HomeServiceAreaSetting::create(HomeServiceAreaSetting::defaultAttributes());
        }

        $this->authorize('update', $setting);

        $data = $request->validated();

        $mapPath = $setting->map_image_path;
        if ($request->hasFile('map_image')) {
            $path = $request->file('map_image')->store('home-service-area', 'public_site');
            if (is_string($mapPath) && $mapPath !== '' && Storage::disk('public_site')->exists($mapPath)) {
                Storage::disk('public_site')->delete($mapPath);
            }
            $mapPath = $path;
        }

        $steps = collect($data['steps'] ?? [])
            ->map(fn ($v) => is_string($v) ? trim($v) : '')
            ->filter(fn ($v) => $v !== '')
            ->values()
            ->all();

        $setting->update([
            'mini_title' => isset($data['mini_title']) && is_string($data['mini_title']) ? trim($data['mini_title']) : null,
            'title' => isset($data['title']) && is_string($data['title']) ? trim($data['title']) : null,
            'highlight_title' => isset($data['highlight_title']) && is_string($data['highlight_title']) ? trim($data['highlight_title']) : null,
            'highlight_description' => isset($data['highlight_description']) && is_string($data['highlight_description']) ? trim($data['highlight_description']) : null,
            'steps' => $steps,
            'map_image_path' => $mapPath,
        ]);

        return redirect()
            ->route('admin.home-sections.service-area')
            ->with('status', 'Service area updated.');
    }

    public function visualFrames(): View
    {
        $this->authorize('viewAny', HomeVisualFramesSetting::class);

        $setting = HomeVisualFramesSetting::query()->first();
        if (! $setting) {
            $setting = new HomeVisualFramesSetting([
                ...HomeVisualFramesSetting::defaultHeader(),
                'gallery' => HomeVisualFramesSetting::defaultGallery(),
                'is_active' => true,
            ]);
        }

        return view('admin.home-sections.visual-frames', [
            'setting' => $setting,
            'headerDefaults' => HomeVisualFramesSetting::defaultHeader(),
            'galleryDefaults' => HomeVisualFramesSetting::defaultGallery(),
        ]);
    }

    public function updateVisualFrames(Request $request): RedirectResponse
    {
        $this->authorize('viewAny', HomeVisualFramesSetting::class);

        $validated = $request->validate([
            'mini_title' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'is_active' => ['sometimes', 'boolean'],
            'items' => ['nullable', 'array'],
            'items.*.url' => ['nullable', 'string', 'max:2048'],
            'items.*.caption' => ['nullable', 'string', 'max:255'],
            'items.*.path' => ['nullable', 'string', 'max:2048'],
            'items.*.file' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:5120'],
            'items.*.remove' => ['nullable', 'in:1,on'],
        ]);

        $setting = HomeVisualFramesSetting::query()->first();
        if (! $setting) {
            $setting = new HomeVisualFramesSetting;
        }

        $itemsInput = $request->input('items', []);
        if (! is_array($itemsInput)) {
            $itemsInput = [];
        }

        $gallery = [];
        foreach ($itemsInput as $idx => $row) {
            if (! is_array($row)) {
                continue;
            }
            if (! empty($row['remove'])) {
                $p = isset($row['path']) && is_string($row['path']) ? trim($row['path']) : '';
                if ($p !== '') {
                    $this->deleteVisualFrameStoredPath($p);
                }

                continue;
            }

            $url = isset($row['url']) && is_string($row['url']) ? trim($row['url']) : '';
            $caption = isset($row['caption']) && is_string($row['caption']) ? trim($row['caption']) : '';
            $path = isset($row['path']) && is_string($row['path']) ? trim($row['path']) : '';
            $path = $path === '' ? null : $path;

            if ($request->hasFile('items.'.$idx.'.file')) {
                if ($path !== null) {
                    $this->deleteVisualFrameStoredPath($path);
                }
                $path = $request->file('items.'.$idx.'.file')->store('home-visual-frames', 'public_site');
                $url = '';
            }

            $urlForDb = $url !== '' ? $url : null;

            if ($path !== null || $urlForDb !== null) {
                $gallery[] = [
                    'path' => $path,
                    'url' => $urlForDb,
                    'caption' => $caption !== '' ? $caption : null,
                ];
            }
        }

        $setting->fill([
            'mini_title' => isset($validated['mini_title']) && is_string($validated['mini_title']) ? trim($validated['mini_title']) : null,
            'title' => isset($validated['title']) && is_string($validated['title']) ? trim($validated['title']) : null,
            'description' => isset($validated['description']) && is_string($validated['description']) ? trim($validated['description']) : null,
            'gallery' => $gallery,
            'is_active' => $request->boolean('is_active'),
        ]);
        $setting->save();

        return redirect()
            ->route('admin.home-sections.visual-frames')
            ->with('status', 'Visual showcase updated.');
    }

    private function deleteVisualFrameStoredPath(string $path): void
    {
        foreach (['public_site', 'public'] as $disk) {
            if (Storage::disk($disk)->exists($path)) {
                Storage::disk($disk)->delete($path);
            }
        }
    }

    public function create(): View
    {
        return view('admin.home-sections.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $contentFieldRule = Rule::in(['mini_title', 'title', 'description', 'points', 'button']);

        $validated = $request->validate([
            'block_type' => ['required', 'in:carousel,two_column'],
            'carousel_variant' => ['nullable', 'in:simple,content_2,news'],
            'two_column_mode' => ['nullable', 'required_if:block_type,two_column', 'in:image_details,both_sides_details'],
            'fields_image' => ['nullable', 'array'],
            'fields_image.*' => ['string', $contentFieldRule],
            'fields_right' => ['nullable', 'array'],
            'fields_right.*' => ['string', $contentFieldRule],
            'fields_left' => ['nullable', 'array'],
            'fields_left.*' => ['string', $contentFieldRule],
        ]);

        if ($validated['block_type'] === 'carousel' && empty($validated['carousel_variant'])) {
            return back()->withErrors(['carousel_variant' => 'Please select a carousel type.'])->withInput();
        }

        if ($validated['block_type'] === 'carousel') {
            $request->session()->put('home_sections_draft', [
                'block_type' => 'carousel',
                'carousel_variant' => $validated['carousel_variant'],
            ]);

            return redirect()->route('admin.home-sections.details');
        }

        $mode = $validated['two_column_mode'] ?? null;
        if ($mode === 'image_details') {
            $fields = array_values(array_unique($validated['fields_image'] ?? []));
            if (count($fields) < 1) {
                return back()->withErrors(['fields_image' => 'Select at least one field next to the image.'])->withInput();
            }
            $request->session()->put('home_sections_draft', [
                'block_type' => 'two_column',
                'two_column_mode' => 'image_details',
                'fields_image' => $fields,
            ]);
        } elseif ($mode === 'both_sides_details') {
            $right = array_values(array_unique($validated['fields_right'] ?? []));
            $left = array_values(array_unique($validated['fields_left'] ?? []));
            if (count($right) < 1) {
                return back()->withErrors(['fields_right' => 'Select at least one field for the right side.'])->withInput();
            }
            if (count($left) < 1) {
                return back()->withErrors(['fields_left' => 'Select at least one field for the left side.'])->withInput();
            }
            $request->session()->put('home_sections_draft', [
                'block_type' => 'two_column',
                'two_column_mode' => 'both_sides_details',
                'fields_right' => $right,
                'fields_left' => $left,
            ]);
        } else {
            return back()->withErrors(['two_column_mode' => 'Choose Image + details or Details on both sides.'])->withInput();
        }

        return redirect()->route('admin.home-sections.details');
    }

    public function details(Request $request): View
    {
        $draft = $request->session()->get('home_sections_draft');

        if (! is_array($draft) || ! in_array(($draft['block_type'] ?? null), ['two_column', 'carousel'], true)) {
            abort(404);
        }

        if (($draft['block_type'] ?? null) === 'carousel') {
            $menus = Menu::query()
                ->with(['subMenus' => fn ($q) => $q->ordered()])
                ->ordered()
                ->get()
                ->filter(fn ($m) => $m->subMenus->count() > 0)
                ->values();

            return view('admin.home-sections.details-carousel-menu', [
                'variant' => $draft['carousel_variant'] ?? null,
                'menus' => $menus,
            ]);
        }

        $mode = $draft['two_column_mode'] ?? null;

        if ($mode === 'image_details') {
            return view('admin.home-sections.details-carousel', [
                'fields' => $draft['fields_image'] ?? [],
            ]);
        }

        if ($mode === 'both_sides_details') {
            return view('admin.home-sections.details-two-column', [
                'fieldsRight' => $draft['fields_right'] ?? [],
                'fieldsLeft' => $draft['fields_left'] ?? [],
            ]);
        }

        abort(404);
    }

    public function saveDetails(Request $request): RedirectResponse
    {
        $draft = $request->session()->get('home_sections_draft');

        if (is_array($draft) && ($draft['block_type'] ?? null) === 'carousel') {
            $validated = $request->validate([
                'menu_id' => ['required', 'integer', 'exists:menus,id'],
                'mini_title' => ['nullable', 'string', 'max:255'],
                'title' => ['required', 'string', 'max:255'],
            ]);

            $menuId = (int) $validated['menu_id'];
            $menuHasSubMenus = Menu::query()
                ->whereKey($menuId)
                ->whereHas('subMenus')
                ->exists();

            if (! $menuHasSubMenus) {
                return back()
                    ->withErrors(['menu_id' => 'Selected menu has no sub menus.'])
                    ->withInput();
            }

            $lastSort = (int) (HomeSection::query()->max('sort_order') ?? 0);
            $nextSortOrder = $lastSort + 1;

            HomeSection::create([
                'block_type' => 'carousel',
                'variant' => (string) ($draft['carousel_variant'] ?? ''),
                'menu_id' => $menuId,
                'mini_title' => $validated['mini_title'] ?? null,
                'title' => $validated['title'],
                'sort_order' => $nextSortOrder,
                'is_active' => true,
            ]);

            $request->session()->forget('home_sections_draft');

            return redirect()
                ->route('admin.home-sections.index')
                ->with('status', 'Carousel saved.');
        }

        if (is_array($draft) && ($draft['block_type'] ?? null) === 'two_column') {
            $mode = $draft['two_column_mode'] ?? null;

            if ($mode === 'image_details') {
                $validated = $request->validate([
                    'layout_width' => ['required', 'in:full,short'],
                    'mini_title' => ['nullable', 'string', 'max:255'],
                    'title' => ['nullable', 'string', 'max:255'],
                    'description' => ['nullable', 'string', 'max:5000'],
                    'points' => ['nullable', 'array'],
                    'points.*' => ['nullable', 'string', 'max:255'],
                    'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:5120'],
                    'image_alt' => ['nullable', 'string', 'max:255'],
                    'button_text' => ['nullable', 'string', 'max:120'],
                    'button_url' => ['nullable', 'string', 'max:2048'],
                ]);

                $lastSort = (int) (HomeSection::query()->max('sort_order') ?? 0);
                $nextSortOrder = $lastSort + 1;

                $imagePath = null;
                if ($request->hasFile('image')) {
                    $imagePath = $request->file('image')->store('home-sections', 'public_site');
                }

                $points = collect($validated['points'] ?? [])
                    ->map(fn ($v) => trim((string) $v))
                    ->filter(fn ($v) => $v !== '')
                    ->values()
                    ->all();

                HomeSection::create([
                    'block_type' => 'two_column',
                    'variant' => 'about',
                    'two_column_mode' => 'image_details',
                    'layout_width' => $validated['layout_width'],
                    'image_path' => $imagePath,
                    'image_alt' => $validated['image_alt'] ?? null,
                    'mini_title' => $validated['mini_title'] ?? 'About Us',
                    'title' => $validated['title'] ?? 'Built on Trust. Driven by Excellence.',
                    'description' => $validated['description'] ?? null,
                    'points' => $points,
                    'button_label' => $validated['button_text'] ?? null,
                    'button_url' => $validated['button_url'] ?? null,
                    'sort_order' => $nextSortOrder,
                    'is_active' => true,
                ]);

                $request->session()->forget('home_sections_draft');

                return redirect()
                    ->route('admin.home-sections.index')
                    ->with('status', 'Two column section saved.');
            }

            if ($mode === 'both_sides_details') {
                $validated = $request->validate([
                    'left_mini_title' => ['nullable', 'string', 'max:255'],
                    'left_title' => ['nullable', 'string', 'max:255'],
                    'left_description' => ['nullable', 'string', 'max:5000'],
                    'left_points' => ['nullable', 'array'],
                    'left_points.*' => ['nullable', 'string', 'max:255'],
                    'left_button_text' => ['nullable', 'string', 'max:120'],
                    'left_button_url' => ['nullable', 'string', 'max:2048'],

                    'right_mini_title' => ['nullable', 'string', 'max:255'],
                    'right_title' => ['nullable', 'string', 'max:255'],
                    'right_description' => ['nullable', 'string', 'max:5000'],
                    'right_points' => ['nullable', 'array'],
                    'right_points.*' => ['nullable', 'string', 'max:255'],
                    'right_button_text' => ['nullable', 'string', 'max:120'],
                    'right_button_url' => ['nullable', 'string', 'max:2048'],
                ]);

                $cleanPoints = fn ($arr) => collect($arr ?? [])
                    ->map(fn ($v) => trim((string) $v))
                    ->filter(fn ($v) => $v !== '')
                    ->values()
                    ->all();

                $left = [
                    'mini_title' => $validated['left_mini_title'] ?? null,
                    'title' => $validated['left_title'] ?? null,
                    'description' => $validated['left_description'] ?? null,
                    'points' => $cleanPoints($validated['left_points'] ?? []),
                    'button_label' => $validated['left_button_text'] ?? null,
                    'button_url' => $validated['left_button_url'] ?? null,
                ];
                $right = [
                    'mini_title' => $validated['right_mini_title'] ?? null,
                    'title' => $validated['right_title'] ?? null,
                    'description' => $validated['right_description'] ?? null,
                    'points' => $cleanPoints($validated['right_points'] ?? []),
                    'button_label' => $validated['right_button_text'] ?? null,
                    'button_url' => $validated['right_button_url'] ?? null,
                ];

                $lastSort = (int) (HomeSection::query()->max('sort_order') ?? 0);
                $nextSortOrder = $lastSort + 1;

                HomeSection::create([
                    'block_type' => 'two_column',
                    'variant' => 'mission_vision',
                    'two_column_mode' => 'both_sides_details',
                    'layout_width' => null,
                    'left_content' => $left,
                    'right_content' => $right,
                    'sort_order' => $nextSortOrder,
                    'is_active' => true,
                ]);

                $request->session()->forget('home_sections_draft');

                return redirect()
                    ->route('admin.home-sections.index')
                    ->with('status', 'Two column section saved.');
            }

            $request->session()->forget('home_sections_draft');

            return redirect()
                ->route('admin.home-sections.index')
                ->with('status', 'Two column details saved.');
        }

        $request->session()->forget('home_sections_draft');

        return redirect()
            ->route('admin.home-sections.index')
            ->with('status', 'Details saved (static UI). Database connection will be added later.');
    }

    public function edit(HomeSection $home_section): View
    {
        if ($home_section->block_type === 'carousel') {
            $menus = Menu::query()
                ->with(['subMenus' => fn ($q) => $q->ordered()])
                ->ordered()
                ->get()
                ->filter(fn ($m) => $m->subMenus->count() > 0)
                ->values();

            return view('admin.home-sections.edit', [
                'section' => $home_section,
                'menus' => $menus,
            ]);
        }

        if ($home_section->block_type === 'two_column' && $home_section->two_column_mode === 'image_details') {
            return view('admin.home-sections.edit-two-column-about', [
                'section' => $home_section,
            ]);
        }

        if ($home_section->block_type === 'two_column' && $home_section->two_column_mode === 'both_sides_details') {
            return view('admin.home-sections.edit-two-column-sides', [
                'section' => $home_section,
            ]);
        }

        abort(404);
    }

    public function update(Request $request, HomeSection $home_section): RedirectResponse
    {
        if ($home_section->block_type === 'carousel') {
            $validated = $request->validate([
                'menu_id' => ['required', 'integer', 'exists:menus,id'],
                'mini_title' => ['nullable', 'string', 'max:255'],
                'title' => ['required', 'string', 'max:255'],
                'sort_order' => ['nullable', 'integer', 'min:0'],
                'is_active' => ['sometimes', 'boolean'],
            ]);

            $validated['is_active'] = $request->boolean('is_active');
            $validated['sort_order'] = $validated['sort_order'] ?? $home_section->sort_order;

            $menuId = (int) $validated['menu_id'];
            $menuHasSubMenus = Menu::query()
                ->whereKey($menuId)
                ->whereHas('subMenus')
                ->exists();

            if (! $menuHasSubMenus) {
                return back()
                    ->withErrors(['menu_id' => 'Selected menu has no sub menus.'])
                    ->withInput();
            }

            $home_section->update([
                'menu_id' => $menuId,
                'mini_title' => $validated['mini_title'] ?? null,
                'title' => $validated['title'],
                'sort_order' => (int) $validated['sort_order'],
                'is_active' => (bool) $validated['is_active'],
            ]);

            return redirect()
                ->route('admin.home-sections.index')
                ->with('status', 'Home section updated.');
        }

        if ($home_section->block_type === 'two_column' && $home_section->two_column_mode === 'image_details') {
            $validated = $request->validate([
                'layout_width' => ['required', 'in:full,short'],
                'mini_title' => ['nullable', 'string', 'max:255'],
                'title' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:5000'],
                'points' => ['nullable', 'array'],
                'points.*' => ['nullable', 'string', 'max:255'],
                'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:5120'],
                'image_alt' => ['nullable', 'string', 'max:255'],
                'button_text' => ['nullable', 'string', 'max:120'],
                'button_url' => ['nullable', 'string', 'max:2048'],
                'sort_order' => ['nullable', 'integer', 'min:0'],
                'is_active' => ['sometimes', 'boolean'],
            ]);

            $validated['is_active'] = $request->boolean('is_active');
            $validated['sort_order'] = $validated['sort_order'] ?? $home_section->sort_order;

            $imagePath = $home_section->image_path;
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('home-sections', 'public_site');

                if (is_string($imagePath) && $imagePath !== '' && Storage::disk('public_site')->exists($imagePath)) {
                    Storage::disk('public_site')->delete($imagePath);
                }
                if (is_string($imagePath) && $imagePath !== '' && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }

                $imagePath = $path;
            }

            $points = collect($validated['points'] ?? [])
                ->map(fn ($v) => trim((string) $v))
                ->filter(fn ($v) => $v !== '')
                ->values()
                ->all();

            $home_section->update([
                'layout_width' => $validated['layout_width'],
                'image_path' => $imagePath,
                'image_alt' => $validated['image_alt'] ?? $home_section->image_alt,
                'mini_title' => $validated['mini_title'] ?? $home_section->mini_title,
                'title' => $validated['title'] ?? $home_section->title,
                'description' => $validated['description'] ?? $home_section->description,
                'points' => $points,
                'button_label' => $validated['button_text'] ?? $home_section->button_label,
                'button_url' => $validated['button_url'] ?? $home_section->button_url,
                'sort_order' => (int) $validated['sort_order'],
                'is_active' => (bool) $validated['is_active'],
            ]);

            return redirect()
                ->route('admin.home-sections.index')
                ->with('status', 'Home section updated.');
        }

        if ($home_section->block_type === 'two_column' && $home_section->two_column_mode === 'both_sides_details') {
            $validated = $request->validate([
                'left_title' => ['nullable', 'string', 'max:255'],
                'left_description' => ['nullable', 'string', 'max:5000'],
                'left_points' => ['nullable', 'array'],
                'left_points.*' => ['nullable', 'string', 'max:255'],
                'right_title' => ['nullable', 'string', 'max:255'],
                'right_description' => ['nullable', 'string', 'max:5000'],
                'right_points' => ['nullable', 'array'],
                'right_points.*' => ['nullable', 'string', 'max:255'],
                'sort_order' => ['nullable', 'integer', 'min:0'],
                'is_active' => ['sometimes', 'boolean'],
            ]);

            $validated['is_active'] = $request->boolean('is_active');
            $validated['sort_order'] = $validated['sort_order'] ?? $home_section->sort_order;

            $cleanPoints = fn ($arr) => collect($arr ?? [])
                ->map(fn ($v) => trim((string) $v))
                ->filter(fn ($v) => $v !== '')
                ->values()
                ->all();

            $left = [
                'title' => $validated['left_title'] ?? null,
                'description' => $validated['left_description'] ?? null,
                'points' => $cleanPoints($validated['left_points'] ?? []),
            ];
            $right = [
                'title' => $validated['right_title'] ?? null,
                'description' => $validated['right_description'] ?? null,
                'points' => $cleanPoints($validated['right_points'] ?? []),
            ];

            $home_section->update([
                'variant' => $home_section->variant ?: 'mission_vision',
                'left_content' => $left,
                'right_content' => $right,
                'sort_order' => (int) $validated['sort_order'],
                'is_active' => (bool) $validated['is_active'],
            ]);

            return redirect()
                ->route('admin.home-sections.index')
                ->with('status', 'Home section updated.');
        }

        abort(404);

        return redirect()
            ->route('admin.home-sections.index')
            ->with('status', 'Home section updated.');
    }

    public function destroy(HomeSection $home_section): RedirectResponse
    {
        $this->authorize('delete', $home_section);

        $path = $home_section->image_path;
        if (is_string($path) && $path !== '') {
            if (Storage::disk('public_site')->exists($path)) {
                Storage::disk('public_site')->delete($path);
            }
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $home_section->delete();

        return redirect()
            ->route('admin.home-sections.index')
            ->with('status', 'Home section removed.');
    }
}
