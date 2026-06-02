<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateServicePageRequest;
use App\Models\Menu;
use App\Models\ServicePage;
use App\Models\SubMenu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ServicePageController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', ServicePage::class);

        return view('admin.service-pages.index', [
            'pages' => ServicePage::query()->orderBy('path')->get(),
        ]);
    }

    public function edit(ServicePage $service_page): View
    {
        $this->authorize('update', $service_page);

        $cardSubMenu = $this->resolveServiceCardSubMenu($service_page);

        return view('admin.service-pages.edit', [
            'page' => $service_page,
            'publicUrl' => ServicePage::publicUrlForSlug($service_page->slug),
            'cardSubMenu' => $cardSubMenu,
            'cardIconUrl' => $cardSubMenu?->coverImageUrl() ?? '',
        ]);
    }

    public function update(UpdateServicePageRequest $request, ServicePage $service_page): RedirectResponse
    {
        $prevHero = $service_page->hero_background;
        $prevContent = $service_page->content_image;
        $prevGallery = is_array($service_page->gallery_images) ? $service_page->gallery_images : [];

        $data = $request->validated();
        unset($data['hero_background_file'], $data['remove_hero_background']);
        unset($data['content_image_file'], $data['remove_content_image']);
        unset($data['card_icon_file'], $data['remove_card_icon']);
        unset(
            $data['gallery_image_0_file'],
            $data['gallery_image_1_file'],
            $data['gallery_image_path_0'],
            $data['gallery_image_path_1'],
            $data['remove_gallery_image_0'],
            $data['remove_gallery_image_1'],
        );
        unset($data['why_card_title'], $data['why_card_icon']);

        if ($request->hasFile('hero_background_file')) {
            $data['hero_background'] = $request->file('hero_background_file')
                ->store(ServicePage::uploadPrefix().'/hero', 'public_site');
        } elseif ($request->boolean('remove_hero_background')) {
            $data['hero_background'] = null;
        }

        if ($request->hasFile('content_image_file')) {
            $data['content_image'] = $request->file('content_image_file')
                ->store(ServicePage::uploadPrefix().'/content', 'public_site');
        } elseif ($request->boolean('remove_content_image')) {
            $data['content_image'] = null;
        }

        $data['gallery_images'] = $this->buildGalleryImages($request, $prevGallery);
        $data['body_paragraphs'] = $this->filterStrings($request->input('body_paragraphs', []));
        $data['why_paragraphs'] = $this->filterStrings($request->input('why_paragraphs', []));
        $data['service_columns'] = $this->normalizeServiceColumns($request->input('service_columns', []));
        $data['why_cards'] = $this->buildWhyCards($request);
        $data['is_active'] = $request->boolean('is_active');

        $service_page->fill($data);
        $service_page->save();

        if ($service_page->hero_background !== $prevHero) {
            ServicePage::deleteManagedUpload($prevHero);
        }
        if ($service_page->content_image !== $prevContent) {
            ServicePage::deleteManagedUpload($prevContent);
        }
        foreach ($prevGallery as $oldPath) {
            if (is_string($oldPath) && $oldPath !== '' && ! in_array($oldPath, $data['gallery_images'], true)) {
                ServicePage::deleteManagedUpload($oldPath);
            }
        }

        $cardSubMenu = $this->resolveServiceCardSubMenu($service_page);
        if ($cardSubMenu) {
            if ($request->hasFile('card_icon_file')) {
                $newPath = $request->file('card_icon_file')->store('sub-menus', 'public_site');

                if ($cardSubMenu->cover_image_path && Storage::disk('public_site')->exists($cardSubMenu->cover_image_path)) {
                    Storage::disk('public_site')->delete($cardSubMenu->cover_image_path);
                }
                if ($cardSubMenu->cover_image_path && Storage::disk('public')->exists($cardSubMenu->cover_image_path)) {
                    Storage::disk('public')->delete($cardSubMenu->cover_image_path);
                }

                $cardSubMenu->cover_image_path = $newPath;
                $cardSubMenu->save();
            } elseif ($request->boolean('remove_card_icon')) {
                if ($cardSubMenu->cover_image_path && Storage::disk('public_site')->exists($cardSubMenu->cover_image_path)) {
                    Storage::disk('public_site')->delete($cardSubMenu->cover_image_path);
                }
                if ($cardSubMenu->cover_image_path && Storage::disk('public')->exists($cardSubMenu->cover_image_path)) {
                    Storage::disk('public')->delete($cardSubMenu->cover_image_path);
                }
                $cardSubMenu->cover_image_path = null;
                $cardSubMenu->save();
            }
        }

        return redirect()
            ->route('admin.service-pages.edit', $service_page)
            ->with('status', 'Service page updated.');
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

    /**
     * @return list<list<string>>
     */
    private function normalizeServiceColumns(mixed $raw): array
    {
        if (! is_array($raw)) {
            return [];
        }

        $columns = [];
        foreach ($raw as $col) {
            if (! is_array($col)) {
                continue;
            }
            $items = $this->filterStrings($col);
            if ($items !== []) {
                $columns[] = $items;
            }
        }

        return $columns;
    }

    /**
     * @return list<array{title: string, icon: string}>
     */
    private function buildWhyCards(Request $request): array
    {
        $titles = $request->input('why_card_title', []);
        if (! is_array($titles)) {
            return [];
        }

        $cards = [];
        foreach (array_keys($titles) as $i) {
            $title = trim((string) ($request->input("why_card_title.{$i}") ?? ''));
            if ($title === '') {
                continue;
            }
            $icon = trim((string) ($request->input("why_card_icon.{$i}") ?? 'team'));

            $cards[] = [
                'title' => $title,
                'icon' => $icon !== '' ? $icon : 'team',
            ];
        }

        return $cards;
    }

    /**
     * @param  list<string|null>  $previous
     * @return list<string>
     */
    private function buildGalleryImages(Request $request, array $previous): array
    {
        $slots = [];
        for ($i = 0; $i < 2; $i++) {
            $path = trim((string) ($request->input("gallery_image_path_{$i}") ?? ''));
            if ($path === '' && isset($previous[$i]) && is_string($previous[$i])) {
                $path = $previous[$i];
            }

            if ($request->hasFile("gallery_image_{$i}_file")) {
                $path = $request->file("gallery_image_{$i}_file")
                    ->store(ServicePage::uploadPrefix().'/gallery', 'public_site');
            } elseif ($request->boolean("remove_gallery_image_{$i}")) {
                $path = '';
            }

            $slots[] = $path;
        }

        return array_values(array_filter($slots, fn (string $p) => $p !== ''));
    }

    private function resolveServiceCardSubMenu(ServicePage $servicePage): ?SubMenu
    {
        $servicesMenu = Menu::ourServicesMenu();
        if (! $servicesMenu) {
            return null;
        }

        $targetPath = trim((string) ($servicePage->path ?? ''));
        $targetPath = $targetPath === '' ? null : (rtrim('/'.ltrim($targetPath, '/'), '/') ?: '/');
        if (! $targetPath) {
            return null;
        }

        return $servicesMenu->subMenus
            ->first(function (SubMenu $sub) use ($targetPath): bool {
                if ((int) ($sub->parent_sub_menu_id ?? 0) !== 0) {
                    return false;
                }

                return $sub->normalizedPath() === $targetPath;
            });
    }
}
