@extends('layouts.admin', ['title' => 'Create sub-menu'])

@section('content')
<div class="header">
    <h1>Create sub-menu</h1>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.sub-menus.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-2">
            <div>
                <label for="menu_id">Parent menu</label>
                <select id="menu_id" name="menu_id" required>
                    <option value="">— Select menu —</option>
                    @foreach ($menus as $menu)
                    <option value="{{ $menu->id }}" @selected(old('menu_id')==$menu->id)>{{ $menu->label }}</option>
                    @endforeach
                </select>
                @error('menu_id') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="label">Sub menu label</label>
                <input id="label" name="label" value="{{ old('label') }}" required>
                @error('label') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="url">Sub menu URL</label>
                <input id="url" name="url" value="{{ old('url') }}" placeholder="Optional (auto if blank)">
                @error('url') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3" placeholder="Short line under the page title (hero)">{{ old('description') }}</textarea>
                @error('description') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="page_content">Page content</label>
                <textarea id="page_content" name="page_content" rows="10" placeholder="Main text for this page (below the hero)">{{ old('page_content') }}</textarea>
                @error('page_content') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="cover_image">Cover image</label>
                <input id="cover_image" name="cover_image" type="file" accept="image/jpeg,image/png,image/webp,image/gif">
                @error('cover_image') <div class="error">{{ $message }}</div> @enderror
                <div style="color:#64748b; font-size:12px; margin-top:6px;">
                    Used on Home page sliders (if set).
                </div>
            </div>
            <div>
                <label for="published_at">Date</label>
                <input id="published_at" name="published_at" type="date" value="{{ old('published_at') }}">
                @error('published_at') <div class="error">{{ $message }}</div> @enderror
                <div style="color:#64748b; font-size:12px; margin-top:6px;">
                    Used in News carousel.
                </div>
            </div>
            <div>
                <label for="sort_order">Sort order</label>
                <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $nextSortOrder ?? 0) }}">
                @error('sort_order') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="is_active">Status</label>
                <select id="is_active" name="is_active">
                    <option value="1" @selected(old('is_active', 1)==1)>Active</option>
                    <option value="0" @selected(old('is_active', 1)==0)>Inactive</option>
                </select>
                @error('is_active') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>
        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit">Save sub-menu</button>
        </div>
    </form>
</div>
@endsection