<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateHomeServiceAreaSettingRequest;
use App\Models\AboutPage;
use App\Models\HomeSection;
use App\Models\HomeServiceAreaSetting;
use App\Models\HomeVisualFramesSetting;
use App\Models\Menu;
use App\Support\ImageUploadRules;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
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
            'branches_mini_title' => isset($data['branches_mini_title']) && is_string($data['branches_mini_title']) ? trim($data['branches_mini_title']) : null,
            'branches_title' => isset($data['branches_title']) && is_string($data['branches_title']) ? trim($data['branches_title']) : null,
            'branches_view_all_label' => isset($data['branches_view_all_label']) && is_string($data['branches_view_all_label']) ? trim($data['branches_view_all_label']) : null,
            'branches_view_all_url' => isset($data['branches_view_all_url']) && is_string($data['branches_view_all_url']) ? trim($data['branches_view_all_url']) : null,
            'branches_items' => $this->mergeServiceAreaBranchItems($request, $setting),
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
        if ($request->filled('section_kind')) {
            return $this->storeCarouselDraft($request);
        }

        return $this->storeInlineSection($request);
    }

    private function storeCarouselDraft(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'section_kind' => ['required', 'in:carousel_simple,carousel_content,carousel_news'],
        ]);

        $variant = match ($validated['section_kind']) {
            'carousel_simple' => 'simple',
            'carousel_content' => 'content_2',
            'carousel_news' => 'news',
        };

        $request->session()->put('home_sections_draft', [
            'block_type' => 'carousel',
            'carousel_variant' => $variant,
        ]);

        return redirect()->route('admin.home-sections.details');
    }

    private function storeInlineSection(Request $request): RedirectResponse
    {
        $attributes = $this->buildInlineSectionAttributes($request);
        $attributes['sort_order'] = (int) (HomeSection::query()->max('sort_order') ?? 0) + 1;

        HomeSection::create($attributes);

        return redirect()
            ->route('admin.home-sections.index')
            ->with('status', 'Home section saved.');
    }

    /**
     * @return array<string, mixed>
     */
    private function buildInlineSectionAttributes(Request $request): array
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
                'image_file' => ImageUploadRules::rules(required: true),
                'video_url' => ['nullable', 'string', 'max:2048'],
                'is_active' => ['sometimes', 'boolean'],
            ],
            'two_column_two_side_details' => [
                'type' => ['required', 'in:two_column_two_side_details'],
                'title' => ['nullable', 'string', 'max:255'],
                'left_title' => ['nullable', 'string', 'max:255'],
                'right_title' => ['nullable', 'string', 'max:255'],
                'left_description' => ['nullable', 'string', 'max:5000'],
                'right_description' => ['nullable', 'string', 'max:5000'],
                'is_active' => ['sometimes', 'boolean'],
            ],
            'two_column_split_cta' => [
                'type' => ['required', 'in:two_column_split_cta'],
                'title' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:5000'],
                'secondary_description' => ['nullable', 'string', 'max:5000'],
                'image_file' => ImageUploadRules::rules(required: true),
                'image_alt' => ['nullable', 'string', 'max:255'],
                'button_label' => ['nullable', 'string', 'max:255'],
                'button_url' => ['nullable', 'string', 'max:2048'],
                'secondary_button_label' => ['nullable', 'string', 'max:255'],
                'secondary_button_url' => ['nullable', 'string', 'max:2048'],
                'is_active' => ['sometimes', 'boolean'],
            ],
            'image' => [
                'type' => ['required', 'in:image'],
                'mini_title' => ['nullable', 'string', 'max:255'],
                'title' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:5000'],
                'image_caption' => ['nullable', 'string', 'max:255'],
                'image_file' => ImageUploadRules::rules(required: true),
                'extra_images' => ['nullable', 'array'],
                'extra_images.*.title' => ['nullable', 'string', 'max:255'],
                'is_active' => ['sometimes', 'boolean'],
            ],
            'text_input' => [
                'type' => ['required', 'in:text_input'],
                'mini_title' => ['nullable', 'string', 'max:255'],
                'title' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:5000'],
                'image_file' => ImageUploadRules::rules(),
                'points' => ['nullable', 'array'],
                'points.*' => ['nullable', 'string', 'max:255'],
                'bottom_description' => ['nullable', 'string', 'max:5000'],
                'is_active' => ['sometimes', 'boolean'],
            ],
            'logo_carousel' => [
                'type' => ['required', 'in:logo_carousel'],
                'title' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:5000'],
                'button_label' => ['nullable', 'string', 'max:255'],
                'button_url' => ['nullable', 'string', 'max:2048'],
                'logo_items' => ['nullable', 'array'],
                'logo_items.*.title' => ['nullable', 'string', 'max:255'],
                'logo_items.*.url' => ['nullable', 'string', 'max:2048'],
                'is_active' => ['sometimes', 'boolean'],
            ],
            default => [
                'type' => ['required', 'in:two_column_image_details,two_column_two_side_details,two_column_split_cta,image,text_input,logo_carousel'],
            ],
        };

        $validated = $request->validate($rules);

        if ($type === 'two_column_image_details') {
            $this->assertValidYoutubeUrl($validated['video_url'] ?? null);
        }

        if ($type === 'image') {
            $this->validateExtraGalleryUploads($request);
        }

        if ($type === 'logo_carousel') {
            $this->validateLogoCarouselUploads($request);
        }

        $isActive = $request->boolean('is_active', true);
        $title = $this->resolveOptionalString($validated['title'] ?? null);

        if ($type === 'two_column_image_details') {
            $points = $this->normalizePoints($validated['points'] ?? null);
            $imagePath = $request->file('image_file')->store('home-sections', 'public_site');

            return [
                'block_type' => 'two_column',
                'variant' => 'about',
                'two_column_mode' => 'image_details',
                'layout_width' => $validated['layout_width'],
                'image_path' => $imagePath,
                'mini_title' => $this->resolveOptionalString($validated['mini_title'] ?? null),
                'title' => $title,
                'description' => $this->resolveOptionalString($validated['description'] ?? null),
                'points' => $points !== [] ? $points : null,
                'data' => $this->twoColumnImageDetailsData($validated),
                'is_active' => $isActive,
            ];
        }

        if ($type === 'two_column_two_side_details') {
            return [
                'block_type' => 'two_column',
                'variant' => 'mission_vision',
                'two_column_mode' => 'both_sides_details',
                'title' => $title,
                'left_content' => [
                    'title' => $this->resolveOptionalString($validated['left_title'] ?? null),
                    'description' => $this->resolveOptionalString($validated['left_description'] ?? null),
                ],
                'right_content' => [
                    'title' => $this->resolveOptionalString($validated['right_title'] ?? null),
                    'description' => $this->resolveOptionalString($validated['right_description'] ?? null),
                ],
                'is_active' => $isActive,
            ];
        }

        if ($type === 'two_column_split_cta') {
            $imagePath = $request->file('image_file')->store('home-sections', 'public_site');

            return [
                'block_type' => 'two_column',
                'variant' => 'recruitment',
                'two_column_mode' => 'split_cta',
                'layout_width' => 'full',
                'image_path' => $imagePath,
                'image_alt' => $this->resolveOptionalString($validated['image_alt'] ?? null),
                'title' => $title,
                'description' => $this->resolveOptionalString($validated['description'] ?? null),
                'button_label' => $this->resolveOptionalString($validated['button_label'] ?? null),
                'button_url' => $this->resolveOptionalString($validated['button_url'] ?? null),
                'data' => $this->splitCtaData($validated),
                'is_active' => $isActive,
            ];
        }

        if ($type === 'image') {
            $mini = $this->resolveOptionalString($validated['mini_title'] ?? null);
            $desc = $this->resolveOptionalString($validated['description'] ?? null);
            $cap = $this->resolveOptionalString($validated['image_caption'] ?? null);

            return [
                'block_type' => 'image',
                'title' => $title,
                'data' => [
                    'mini_title' => $mini,
                    'description' => $desc,
                    'image_caption' => $cap,
                    'image_path' => $request->file('image_file')->store('home-sections', 'public_site'),
                    'extra_gallery' => $this->collectNewExtraGalleryFiles($request, 'home-sections'),
                ],
                'is_active' => $isActive,
            ];
        }

        if ($type === 'text_input') {
            $mini = $this->resolveOptionalString($validated['mini_title'] ?? null);
            $desc = $this->resolveOptionalString($validated['description'] ?? null);
            $bottom = $this->resolveOptionalString($validated['bottom_description'] ?? null);
            $points = $this->normalizePoints($validated['points'] ?? null);
            $imagePath = $request->hasFile('image_file')
                ? $request->file('image_file')->store('home-sections', 'public_site')
                : null;

            return [
                'block_type' => 'text_input',
                'title' => $title,
                'data' => [
                    'mini_title' => $mini,
                    'description' => $desc,
                    'points' => $points !== [] ? $points : null,
                    'bottom_description' => $bottom,
                    'image_path' => $imagePath,
                ],
                'is_active' => $isActive,
            ];
        }

        if ($type === 'logo_carousel') {
            return [
                'block_type' => 'logo_carousel',
                'variant' => 'certificates',
                'title' => $title,
                'description' => $this->resolveOptionalString($validated['description'] ?? null),
                'button_label' => $this->resolveOptionalString($validated['button_label'] ?? null),
                'button_url' => $this->resolveOptionalString($validated['button_url'] ?? null),
                'data' => [
                    'items' => $this->collectNewLogoCarouselFiles($request, 'home-sections'),
                ],
                'is_active' => $isActive,
            ];
        }

        throw ValidationException::withMessages([
            'type' => ['Select a section type.'],
        ]);
    }

    private function resolveOptionalString(mixed $value): ?string
    {
        $value = is_string($value) ? trim($value) : '';

        return $value !== '' ? $value : null;
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

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function twoColumnImageDetailsData(array $validated): array
    {
        return [
            'image_side' => $this->normalizeImageSide($validated['image_side'] ?? 'left'),
            'video_url' => $this->resolveOptionalString($validated['video_url'] ?? null),
        ];
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function splitCtaData(array $validated): array
    {
        return [
            'secondary_description' => $this->sanitizeRichTextOptional($validated['secondary_description'] ?? null),
            'secondary_button_label' => $this->resolveOptionalString($validated['secondary_button_label'] ?? null),
            'secondary_button_url' => $this->resolveOptionalString($validated['secondary_button_url'] ?? null),
        ];
    }

    private function sanitizeRichTextOptional(mixed $value): ?string
    {
        $value = is_string($value) ? trim($value) : '';
        if ($value === '') {
            return null;
        }

        return strip_tags($value, '<strong><b><a><br><em>');
    }

    private function assertValidYoutubeUrl(mixed $url): void
    {
        if (! is_string($url) || trim($url) === '') {
            return;
        }

        if (AboutPage::videoModalPayload($url)['type'] === 'none') {
            throw ValidationException::withMessages([
                'video_url' => 'Use a valid YouTube link (watch, youtu.be, or embed URL).',
            ]);
        }
    }

    private function validateExtraGalleryUploads(Request $request, string $inputKey = 'extra_images'): void
    {
        $uploaded = $request->file($inputKey);
        if (! is_array($uploaded)) {
            return;
        }

        $rules = [];
        foreach ($uploaded as $i => $row) {
            if (! is_array($row)) {
                continue;
            }

            $file = $row['file'] ?? null;
            if (! $file instanceof UploadedFile) {
                continue;
            }

            if (! $file->isValid()) {
                throw ValidationException::withMessages([
                    $inputKey.'.'.$i.'.file' => [ImageUploadRules::invalidUploadMessage($file)],
                ]);
            }

            $rules[$inputKey.'.'.$i.'.file'] = ImageUploadRules::rules(required: true);
        }

        if ($rules !== []) {
            Validator::make($request->all(), $rules)->validate();
        }
    }

    /**
     * @return list<array{path: string, title: string|null}>
     */
    private function collectNewExtraGalleryFiles(Request $request, string $storageDir, string $inputKey = 'extra_images'): array
    {
        $out = [];
        $uploaded = $request->file($inputKey);
        if (! is_array($uploaded)) {
            return $out;
        }

        foreach ($uploaded as $i => $row) {
            if (! is_array($row)) {
                continue;
            }

            $file = $row['file'] ?? null;
            if (! $file instanceof UploadedFile || ! $file->isValid()) {
                continue;
            }

            $path = $file->store($storageDir, 'public_site');
            $title = trim((string) $request->input($inputKey.'.'.$i.'.title', ''));

            $out[] = ['path' => $path, 'title' => $title !== '' ? $title : null];
        }

        return $out;
    }

    private function validateLogoCarouselUploads(Request $request): void
    {
        $this->validateExtraGalleryUploads($request, 'logo_items');
    }

    /**
     * @return list<array{path: string, title: string|null, url: string|null}>
     */
    private function collectNewLogoCarouselFiles(Request $request, string $storageDir): array
    {
        $out = [];
        $uploaded = $request->file('logo_items');
        $inputs = $request->input('logo_items', []);
        if (! is_array($uploaded)) {
            $uploaded = [];
        }
        if (! is_array($inputs)) {
            $inputs = [];
        }

        $keys = array_unique(array_merge(array_keys($uploaded), array_keys($inputs)));

        foreach ($keys as $i) {
            $row = is_array($uploaded[$i] ?? null) ? $uploaded[$i] : [];
            $file = $row['file'] ?? null;
            $title = trim((string) $request->input('logo_items.'.$i.'.title', ''));
            $url = trim((string) $request->input('logo_items.'.$i.'.url', ''));

            if ($file instanceof UploadedFile && $file->isValid()) {
                $path = $file->store($storageDir, 'public_site');
                $out[] = [
                    'path' => $path,
                    'title' => $title !== '' ? $title : null,
                    'url' => $url !== '' ? $url : null,
                ];

                continue;
            }

            if ($title !== '') {
                $out[] = [
                    'path' => null,
                    'title' => $title,
                    'url' => $url !== '' ? $url : null,
                ];
            }
        }

        return $out;
    }

    /**
     * @return list<array{path: string, title: string|null, url: string|null}>
     */
    private function mergeLogoCarouselItems(Request $request, HomeSection $section): array
    {
        $current = is_array($section->data) ? $section->data : [];
        $items = data_get($current, 'items', []);
        if (! is_array($items)) {
            $items = [];
        }
        $items = array_values($items);

        $remove = array_map('intval', (array) $request->input('logo_remove', []));
        $kept = [];
        foreach ($items as $idx => $item) {
            if (! is_array($item)) {
                continue;
            }
            if (in_array((int) $idx, $remove, true)) {
                $this->deleteStoredPath(data_get($item, 'path'));

                continue;
            }
            $path = data_get($item, 'path');
            $path = is_string($path) && trim($path) !== '' ? trim($path) : null;
            $title = $request->input('logo_titles.'.$idx, data_get($item, 'title'));
            $url = $request->input('logo_urls.'.$idx, data_get($item, 'url'));
            $title = is_string($title) ? trim($title) : '';
            $url = is_string($url) ? trim($url) : '';
            if ($path === null && $title === '') {
                continue;
            }
            $kept[] = [
                'path' => $path,
                'title' => $title !== '' ? $title : null,
                'url' => $url !== '' ? $url : null,
            ];
        }

        return array_merge($kept, $this->collectNewLogoCarouselFiles($request, 'home-sections'));
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

        return view('admin.home-sections.edit-section', [
            'section' => $home_section,
            'formType' => $this->formTypeFor($home_section),
        ]);
    }

    private function formTypeFor(HomeSection $section): string
    {
        if ($section->block_type === 'image') {
            return 'image';
        }

        if ($section->block_type === 'text_input') {
            return 'text_input';
        }

        if ($section->block_type === 'two_column' && $section->two_column_mode === 'image_details') {
            return 'two_column_image_details';
        }

        if ($section->block_type === 'two_column' && $section->two_column_mode === 'both_sides_details') {
            return 'two_column_two_side_details';
        }

        if ($section->block_type === 'two_column' && $section->two_column_mode === 'split_cta') {
            return 'two_column_split_cta';
        }

        if ($section->block_type === 'logo_carousel') {
            return 'logo_carousel';
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

        $attributes = $this->buildInlineSectionUpdateAttributes($request, $home_section);
        $home_section->update($attributes);

        return redirect()
            ->route('admin.home-sections.index')
            ->with('status', 'Home section updated.');
    }

    /**
     * @return array<string, mixed>
     */
    private function buildInlineSectionUpdateAttributes(Request $request, HomeSection $section): array
    {
        $type = $this->formTypeFor($section);

        if ($type === 'two_column_image_details') {
            $validated = $request->validate([
                'type' => ['required', 'in:two_column_image_details'],
                'layout_width' => ['required', 'in:full,short'],
                'mini_title' => ['nullable', 'string', 'max:255'],
                'title' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:5000'],
                'points' => ['nullable', 'array'],
                'points.*' => ['nullable', 'string', 'max:255'],
                'image_side' => ['required', 'in:left,right'],
                'image_file' => ImageUploadRules::rules(),
                'video_url' => ['nullable', 'string', 'max:2048'],
                'sort_order' => ['nullable', 'integer', 'min:0'],
                'is_active' => ['sometimes', 'boolean'],
            ]);

            $this->assertValidYoutubeUrl($validated['video_url'] ?? null);

            $imagePath = $section->image_path;
            if ($request->hasFile('image_file')) {
                $this->deleteStoredPath($imagePath);
                $imagePath = $request->file('image_file')->store('home-sections', 'public_site');
            }

            $points = $this->normalizePoints($validated['points'] ?? null);

            return [
                'layout_width' => $validated['layout_width'],
                'image_path' => $imagePath,
                'mini_title' => $this->resolveOptionalString($validated['mini_title'] ?? null),
                'title' => $this->resolveOptionalString($validated['title'] ?? null),
                'description' => $this->resolveOptionalString($validated['description'] ?? null),
                'points' => $points !== [] ? $points : null,
                'data' => $this->twoColumnImageDetailsData($validated),
                'sort_order' => (int) ($validated['sort_order'] ?? $section->sort_order),
                'is_active' => $request->boolean('is_active'),
            ];
        }

        if ($type === 'two_column_two_side_details') {
            $validated = $request->validate([
                'type' => ['required', 'in:two_column_two_side_details'],
                'title' => ['nullable', 'string', 'max:255'],
                'left_title' => ['nullable', 'string', 'max:255'],
                'right_title' => ['nullable', 'string', 'max:255'],
                'left_description' => ['nullable', 'string', 'max:5000'],
                'right_description' => ['nullable', 'string', 'max:5000'],
                'sort_order' => ['nullable', 'integer', 'min:0'],
                'is_active' => ['sometimes', 'boolean'],
            ]);

            return [
                'title' => $this->resolveOptionalString($validated['title'] ?? null),
                'left_content' => [
                    'title' => $this->resolveOptionalString($validated['left_title'] ?? null),
                    'description' => $this->resolveOptionalString($validated['left_description'] ?? null),
                ],
                'right_content' => [
                    'title' => $this->resolveOptionalString($validated['right_title'] ?? null),
                    'description' => $this->resolveOptionalString($validated['right_description'] ?? null),
                ],
                'sort_order' => (int) ($validated['sort_order'] ?? $section->sort_order),
                'is_active' => $request->boolean('is_active'),
            ];
        }

        if ($type === 'two_column_split_cta') {
            $validated = $request->validate([
                'type' => ['required', 'in:two_column_split_cta'],
                'title' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:5000'],
                'secondary_description' => ['nullable', 'string', 'max:5000'],
                'image_file' => ImageUploadRules::rules(),
                'image_alt' => ['nullable', 'string', 'max:255'],
                'button_label' => ['nullable', 'string', 'max:255'],
                'button_url' => ['nullable', 'string', 'max:2048'],
                'secondary_button_label' => ['nullable', 'string', 'max:255'],
                'secondary_button_url' => ['nullable', 'string', 'max:2048'],
                'sort_order' => ['nullable', 'integer', 'min:0'],
                'is_active' => ['sometimes', 'boolean'],
            ]);

            $imagePath = $section->image_path;
            if ($request->hasFile('image_file')) {
                $this->deleteStoredPath($imagePath);
                $imagePath = $request->file('image_file')->store('home-sections', 'public_site');
            }

            return [
                'image_path' => $imagePath,
                'image_alt' => $this->resolveOptionalString($validated['image_alt'] ?? null),
                'title' => $this->resolveOptionalString($validated['title'] ?? null),
                'description' => $this->resolveOptionalString($validated['description'] ?? null),
                'button_label' => $this->resolveOptionalString($validated['button_label'] ?? null),
                'button_url' => $this->resolveOptionalString($validated['button_url'] ?? null),
                'data' => $this->splitCtaData($validated),
                'sort_order' => (int) ($validated['sort_order'] ?? $section->sort_order),
                'is_active' => $request->boolean('is_active'),
            ];
        }

        if ($type === 'image') {
            $validated = $request->validate([
                'type' => ['required', 'in:image'],
                'mini_title' => ['nullable', 'string', 'max:255'],
                'title' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:5000'],
                'image_caption' => ['nullable', 'string', 'max:255'],
                'image_file' => ImageUploadRules::rules(),
                'extra_images' => ['nullable', 'array'],
                'extra_images.*.title' => ['nullable', 'string', 'max:255'],
                'extra_remove' => ['nullable', 'array'],
                'extra_remove.*' => ['integer', 'min:0'],
                'extra_gallery_titles' => ['nullable', 'array'],
                'extra_gallery_titles.*' => ['nullable', 'string', 'max:255'],
                'sort_order' => ['nullable', 'integer', 'min:0'],
                'is_active' => ['sometimes', 'boolean'],
            ]);

            $this->validateExtraGalleryUploads($request);

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

            $data = [
                'mini_title' => $this->resolveOptionalString($validated['mini_title'] ?? null),
                'description' => $this->resolveOptionalString($validated['description'] ?? null),
                'image_caption' => $this->resolveOptionalString($validated['image_caption'] ?? null),
                'image_path' => data_get($current, 'image_path'),
                'extra_gallery' => array_merge($kept, $this->collectNewExtraGalleryFiles($request, 'home-sections')),
            ];

            if ($request->hasFile('image_file')) {
                $this->deleteStoredPath($data['image_path']);
                $data['image_path'] = $request->file('image_file')->store('home-sections', 'public_site');
            }

            return [
                'title' => $this->resolveOptionalString($validated['title'] ?? null),
                'data' => $data,
                'sort_order' => (int) ($validated['sort_order'] ?? $section->sort_order),
                'is_active' => $request->boolean('is_active'),
            ];
        }

        if ($type === 'text_input') {
            $validated = $request->validate([
                'type' => ['required', 'in:text_input'],
                'mini_title' => ['nullable', 'string', 'max:255'],
                'title' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:5000'],
                'image_file' => ImageUploadRules::rules(),
                'points' => ['nullable', 'array'],
                'points.*' => ['nullable', 'string', 'max:255'],
                'bottom_description' => ['nullable', 'string', 'max:5000'],
                'sort_order' => ['nullable', 'integer', 'min:0'],
                'is_active' => ['sometimes', 'boolean'],
            ]);

            $current = is_array($section->data) ? $section->data : [];
            $points = $this->normalizePoints($validated['points'] ?? null);

            $data = [
                'mini_title' => $this->resolveOptionalString($validated['mini_title'] ?? null),
                'description' => $this->resolveOptionalString($validated['description'] ?? null),
                'points' => $points !== [] ? $points : null,
                'bottom_description' => $this->resolveOptionalString($validated['bottom_description'] ?? null),
                'image_path' => data_get($current, 'image_path'),
            ];

            if ($request->hasFile('image_file')) {
                $this->deleteStoredPath($data['image_path']);
                $data['image_path'] = $request->file('image_file')->store('home-sections', 'public_site');
            }

            return [
                'title' => $this->resolveOptionalString($validated['title'] ?? null),
                'data' => $data,
                'sort_order' => (int) ($validated['sort_order'] ?? $section->sort_order),
                'is_active' => $request->boolean('is_active'),
            ];
        }

        if ($type === 'logo_carousel') {
            $validated = $request->validate([
                'type' => ['required', 'in:logo_carousel'],
                'title' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:5000'],
                'button_label' => ['nullable', 'string', 'max:255'],
                'button_url' => ['nullable', 'string', 'max:2048'],
                'logo_items' => ['nullable', 'array'],
                'logo_items.*.title' => ['nullable', 'string', 'max:255'],
                'logo_items.*.url' => ['nullable', 'string', 'max:2048'],
                'logo_remove' => ['nullable', 'array'],
                'logo_remove.*' => ['integer', 'min:0'],
                'logo_titles' => ['nullable', 'array'],
                'logo_titles.*' => ['nullable', 'string', 'max:255'],
                'logo_urls' => ['nullable', 'array'],
                'logo_urls.*' => ['nullable', 'string', 'max:2048'],
                'sort_order' => ['nullable', 'integer', 'min:0'],
                'is_active' => ['sometimes', 'boolean'],
            ]);

            $this->validateLogoCarouselUploads($request);

            return [
                'title' => $this->resolveOptionalString($validated['title'] ?? null),
                'description' => $this->resolveOptionalString($validated['description'] ?? null),
                'button_label' => $this->resolveOptionalString($validated['button_label'] ?? null),
                'button_url' => $this->resolveOptionalString($validated['button_url'] ?? null),
                'data' => [
                    'items' => $this->mergeLogoCarouselItems($request, $section),
                ],
                'sort_order' => (int) ($validated['sort_order'] ?? $section->sort_order),
                'is_active' => $request->boolean('is_active'),
            ];
        }

        abort(404);
    }

    /**
     * @return list<array{path: string, url: string|null, label: string|null}>
     */
    private function mergeServiceAreaBranchItems(Request $request, HomeServiceAreaSetting $setting): array
    {
        $existing = is_array($setting->branches_items) ? $setting->branches_items : [];
        $inputs = $request->input('branch_items', []);
        $uploads = $request->file('branch_items');
        $max = max(count($existing), is_array($inputs) ? count($inputs) : 0, is_array($uploads) ? count($uploads) : 0);
        $out = [];

        for ($i = 0; $i < $max; $i++) {
            $prev = is_array($existing[$i] ?? null) ? $existing[$i] : [];
            $path = is_string($prev['path'] ?? null) ? trim($prev['path']) : '';
            $existingPath = trim((string) $request->input('branch_items.'.$i.'.existing_path', $path));
            if ($existingPath !== '') {
                $path = $existingPath;
            }

            $file = is_array($uploads) ? ($uploads[$i]['file'] ?? null) : null;
            if ($file instanceof UploadedFile) {
                if ($path !== '') {
                    $this->deleteStoredPath($path);
                }
                $path = $file->store('home-service-area/branches', 'public_site');
            }

            if ($path === '') {
                continue;
            }

            $label = trim((string) $request->input('branch_items.'.$i.'.label', ''));
            $subtitle = trim((string) $request->input('branch_items.'.$i.'.subtitle', ''));
            $url = trim((string) $request->input('branch_items.'.$i.'.url', ''));

            $out[] = [
                'path' => $path,
                'label' => $label !== '' ? $label : null,
                'subtitle' => $subtitle !== '' ? $subtitle : null,
                'url' => $url !== '' ? $url : null,
            ];
        }

        return $out;
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

    private function deleteSectionFiles(HomeSection $section): void
    {
        if ($section->block_type === 'two_column' && in_array($section->two_column_mode, ['image_details', 'split_cta'], true)) {
            $this->deleteStoredPath($section->image_path);

            return;
        }

        if ($section->block_type === 'image') {
            $data = is_array($section->data) ? $section->data : [];
            $this->deleteStoredPath(data_get($data, 'image_path'));
            $gallery = data_get($data, 'extra_gallery', []);
            if (is_array($gallery)) {
                foreach ($gallery as $item) {
                    $this->deleteStoredPath(data_get($item, 'path'));
                }
            }

            return;
        }

        if ($section->block_type === 'text_input') {
            $data = is_array($section->data) ? $section->data : [];
            $this->deleteStoredPath(data_get($data, 'image_path'));

            return;
        }

        if ($section->block_type === 'logo_carousel') {
            $data = is_array($section->data) ? $section->data : [];
            $items = data_get($data, 'items', []);
            if (is_array($items)) {
                foreach ($items as $item) {
                    $this->deleteStoredPath(data_get($item, 'path'));
                }
            }
        }
    }

    public function destroy(HomeSection $home_section): RedirectResponse
    {
        $this->authorize('delete', $home_section);

        $this->deleteSectionFiles($home_section);

        $home_section->delete();

        return redirect()
            ->route('admin.home-sections.index')
            ->with('status', 'Home section removed.');
    }
}
