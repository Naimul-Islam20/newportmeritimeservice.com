@extends('layouts.admin', ['title' => 'Create Ship Supply sub-menu'])

@section('content')
<div class="header">
    <h1>Create sub-menu</h1>
    <a class="btn btn-muted" href="{{ route('admin.ship-supply-sub-menus.index') }}">Back to list</a>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.ship-supply-sub-menus.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="menu_id" value="{{ $menu->id }}">

        <div class="grid grid-2">
            <div>
                <label for="label">Menu label</label>
                <input id="label" name="label" value="{{ old('label') }}" required placeholder="e.g. Provision And Bond Store">
                @error('label') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="url">URL (optional)</label>
                <input id="url" name="url" value="{{ old('url') }}" placeholder="e.g. /ship-supply/provision-and-bond-store">
                @error('url') <div class="error">{{ $message }}</div> @enderror
                <div style="color:#64748b;font-size:12px;margin-top:6px;">Leave blank to auto-generate under /ship-supply/…</div>
            </div>
            <div>
                <label for="sort_order">Sort order</label>
                <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $nextSortOrder) }}">
                @error('sort_order') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="is_active">Status</label>
                <select id="is_active" name="is_active">
                    <option value="1" @selected(old('is_active', 1) == 1)>Active</option>
                    <option value="0" @selected(old('is_active', 1) == 0)>Inactive</option>
                </select>
                @error('is_active') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="description">Description (optional)</label>
                <textarea id="description" name="description" rows="2" placeholder="Short text shown on the homepage card">{{ old('description') }}</textarea>
                @error('description') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="icon_image">Icon Image (shown on homepage card)</label>
                <input id="icon_image" name="icon_image" type="file" accept="image/png,image/jpeg,image/webp,image/svg+xml">
                <div style="color:#64748b;font-size:12px;margin-top:4px;">Recommended: transparent PNG or SVG, square, ~80×80px. Max 2MB.</div>
                @error('icon_image') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit">Create sub-menu</button>
        </div>
    </form>
</div>
@endsection
