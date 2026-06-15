@extends('layouts.admin', ['title' => 'Edit Our Services sub-menu'])

@section('content')
<div class="header">
    <h1>Edit sub-menu</h1>
    <div style="display:inline-flex;gap:8px;flex-wrap:wrap;">
        <a class="btn btn-muted" href="{{ route('admin.our-services-sub-menus.index') }}">Back to list</a>
        @if (! $subMenu->isFormPageLink())
        <a class="btn btn-primary" href="{{ $subMenu->adminSidebarHref() }}">Page content</a>
        @endif
    </div>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.our-services-sub-menus.update', $subMenu) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="menu_id" value="{{ $menu->id }}">

        <div class="grid grid-2">
            <div>
                <label for="label">Menu label</label>
                <input id="label" name="label" value="{{ old('label', $subMenu->label) }}" required>
                @error('label') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="url">URL</label>
                <input id="url" name="url" value="{{ old('url', $subMenu->url) }}" placeholder="/our-services/bunkering-service">
                @error('url') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="sort_order">Sort order</label>
                <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $subMenu->sort_order) }}">
                @error('sort_order') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="is_active">Status</label>
                <select id="is_active" name="is_active">
                    <option value="1" @selected(old('is_active', $subMenu->is_active) == 1)>Active</option>
                    <option value="0" @selected(old('is_active', $subMenu->is_active) == 0)>Inactive</option>
                </select>
                @error('is_active') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="description">Description (optional)</label>
                <textarea id="description" name="description" rows="2">{{ old('description', $subMenu->description) }}</textarea>
                @error('description') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="icon_image">Icon Image (shown on homepage card)</label>
                @if ($subMenu->iconImageUrl() !== '')
                    <div style="margin-bottom:8px;">
                        <img src="{{ $subMenu->iconImageUrl() }}" alt="Current icon" style="max-height:80px;max-width:120px;object-fit:contain;border:1px solid #e2e8f0;border-radius:6px;padding:4px;background:#f8fafc;">
                        <div style="color:#64748b;font-size:12px;margin-top:2px;">Current icon — upload a new file to replace it.</div>
                    </div>
                @endif
                <input id="icon_image" name="icon_image" type="file" accept="image/png,image/jpeg,image/webp,image/svg+xml">
                <div style="color:#64748b;font-size:12px;margin-top:4px;">Recommended: transparent PNG or SVG, square, ~80×80px. Max 2MB.</div>
                @error('icon_image') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit">Save changes</button>
        </div>
    </form>
</div>
@endsection
