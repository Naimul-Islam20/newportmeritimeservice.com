<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreHeroSlideRequest;
use App\Http\Requests\Admin\UpdateHeroSlideRequest;
use App\Models\HeroSlide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HeroSlideController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', HeroSlide::class);

        $slides = HeroSlide::query()->ordered()->get();
        $nextSortOrder = ((int) (HeroSlide::query()->max('sort_order') ?? 0)) + 1;

        return view('admin.hero-slides.index', [
            'slides' => $slides,
            'nextSortOrder' => $nextSortOrder,
        ]);
    }

    public function store(StoreHeroSlideRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $path = $request->file('image')->store('hero-slides', 'public_site');

        if (! array_key_exists('sort_order', $data) || $data['sort_order'] === null) {
            $data['sort_order'] = ((int) (HeroSlide::query()->max('sort_order') ?? 0)) + 1;
        }

        HeroSlide::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'button_label' => $data['button_label'] ?? null,
            'button_url' => $data['button_url'] ?? null,
            'image_path' => $path,
            'sort_order' => (int) $data['sort_order'],
        ]);

        return redirect()
            ->route('admin.hero-slides.index')
            ->with('status', 'Hero slide added.');
    }

    public function edit(HeroSlide $hero_slide): View
    {
        $this->authorize('update', $hero_slide);

        return view('admin.hero-slides.edit', [
            'slide' => $hero_slide,
        ]);
    }

    public function update(UpdateHeroSlideRequest $request, HeroSlide $hero_slide): RedirectResponse
    {
        $data = $request->validated();

        $payload = [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'button_label' => $data['button_label'] ?? null,
            'button_url' => $data['button_url'] ?? null,
            'sort_order' => (int) ($data['sort_order'] ?? $hero_slide->sort_order),
        ];

        if ($request->hasFile('image')) {
            $oldPath = $hero_slide->image_path;
            $payload['image_path'] = $request->file('image')->store('hero-slides', 'public_site');

            if (is_string($oldPath) && $oldPath !== '') {
                if (Storage::disk('public_site')->exists($oldPath)) {
                    Storage::disk('public_site')->delete($oldPath);
                }
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
        }

        $hero_slide->update($payload);

        return redirect()
            ->route('admin.hero-slides.index')
            ->with('status', 'Hero slide updated.');
    }

    public function destroy(HeroSlide $heroSlide): RedirectResponse
    {
        $this->authorize('delete', $heroSlide);

        if ($heroSlide->image_path) {
            if (Storage::disk('public_site')->exists($heroSlide->image_path)) {
                Storage::disk('public_site')->delete($heroSlide->image_path);
            }
            if (Storage::disk('public')->exists($heroSlide->image_path)) {
                Storage::disk('public')->delete($heroSlide->image_path);
            }
        }

        $heroSlide->delete();

        return redirect()
            ->route('admin.hero-slides.index')
            ->with('status', 'Hero slide removed.');
    }
}
