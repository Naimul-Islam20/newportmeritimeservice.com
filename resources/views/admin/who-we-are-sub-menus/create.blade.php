@extends('layouts.admin', ['title' => 'Create Who We Are sub-menu'])

@section('content')
<div class="header">
    <h1>Create sub-menu</h1>
    <a class="btn btn-muted" href="{{ route('admin.who-we-are-sub-menus.index') }}">Back to list</a>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.who-we-are-sub-menus.store') }}">
        @csrf
        <input type="hidden" name="menu_id" value="{{ $menu->id }}">

        <div class="grid grid-2">
            <div>
                <label for="label">Menu label</label>
                <input id="label" name="label" value="{{ old('label') }}" required placeholder="e.g. About Us">
                @error('label') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="url">URL (optional)</label>
                <input id="url" name="url" value="{{ old('url') }}" placeholder="e.g. /about-us or /who-we-are/my-page">
                @error('url') <div class="error">{{ $message }}</div> @enderror
                <div style="color:#64748b;font-size:12px;margin-top:6px;">Leave blank to auto-generate from the label.</div>
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
                <textarea id="description" name="description" rows="2" placeholder="Short line under the page title">{{ old('description') }}</textarea>
                @error('description') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit">Create sub-menu</button>
        </div>
    </form>
</div>
@endsection
