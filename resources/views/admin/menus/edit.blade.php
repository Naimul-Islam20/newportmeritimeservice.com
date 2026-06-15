@extends('layouts.admin', ['title' => 'Edit menu'])

@section('content')
<div class="header">
    <h1>Edit menu</h1>
    <a class="btn btn-primary" href="{{ route('admin.menus.page-sections.index', $menu) }}">Page sections</a>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.menus.update', $menu) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid grid-2">
            @include('admin.menus.partials.show-submenu-pages-toggle', ['defaultShow' => $menu->show_submenus_on_page])
            <div>
                <label for="label">Label</label>
                <input id="label" name="label" value="{{ old('label', $menu->label) }}" required>
                @error('label') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="url">URL</label>
                <input id="url" name="url" value="{{ old('url', $menu->url) }}" placeholder="Optional (auto if blank)">
                @error('url') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3" placeholder="Short line under the page title (hero)">{{ old('description', $menu->description) }}</textarea>
                @error('description') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="page_content">Page content</label>
                <textarea id="page_content" name="page_content" rows="10" placeholder="Main text for this page (below the hero)">{{ old('page_content', $menu->page_content) }}</textarea>
                @error('page_content') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="cover_image">Cover Image (Banner)</label>
                @if ($menu->cover_image_path)
                    <div style="margin-bottom:8px;">
                        <img src="{{ $menu->coverImageUrl() }}" alt="Current cover" style="max-height:120px;max-width:300px;object-fit:cover;border:1px solid #e2e8f0;border-radius:6px;">
                        <div style="color:#64748b;font-size:12px;margin-top:2px;">Current cover — upload a new file to replace it.</div>
                    </div>
                @endif
                <input id="cover_image" name="cover_image" type="file" accept="image/png,image/jpeg,image/webp,image/svg+xml">
                <div style="color:#64748b;font-size:12px;margin-top:4px;">Recommended: High resolution landscape image. Max 5MB.</div>
                @error('cover_image') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="sort_order">Sort order</label>
                <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $menu->sort_order) }}">
                @error('sort_order') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="is_active">Status</label>
                <select id="is_active" name="is_active">
                    <option value="1" @selected(old('is_active', $menu->is_active) == 1)>Active</option>
                    <option value="0" @selected(old('is_active', $menu->is_active) == 0)>Inactive</option>
                </select>
                @error('is_active') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>
        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit">Update menu</button>
        </div>
    </form>
</div>
@endsection