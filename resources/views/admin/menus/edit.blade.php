@extends('layouts.admin', ['title' => 'Edit menu'])

@section('content')
<div class="header">
    <h1>Edit menu</h1>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.menus.update', $menu) }}">
        @csrf
        @method('PUT')
        <div class="grid grid-2">
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
                <textarea id="description" name="description" rows="3" placeholder="Optional short description">{{ old('description', $menu->description) }}</textarea>
                @error('description') <div class="error">{{ $message }}</div> @enderror
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
