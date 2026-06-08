@extends('layouts.admin', ['title' => 'Edit Ship Supply sub-menu'])

@section('content')
<div class="header">
    <h1>Edit sub-menu</h1>
    <div style="display:inline-flex;gap:8px;flex-wrap:wrap;">
        <a class="btn btn-muted" href="{{ route('admin.ship-supply-sub-menus.index') }}">Back to list</a>
        @if (! $subMenu->isFormPageLink())
        <a class="btn btn-primary" href="{{ $subMenu->adminSidebarHref() }}">Page content</a>
        @endif
    </div>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.ship-supply-sub-menus.update', $subMenu) }}">
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
                <input id="url" name="url" value="{{ old('url', $subMenu->url) }}" placeholder="/ship-supply/provision-and-bond-store">
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
        </div>

        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit">Save changes</button>
        </div>
    </form>
</div>
@endsection
