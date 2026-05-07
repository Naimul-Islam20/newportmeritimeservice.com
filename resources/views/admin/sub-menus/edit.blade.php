@extends('layouts.admin', ['title' => 'Edit sub-menu'])

@section('content')
<div class="header">
    <h1>Edit sub-menu</h1>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.sub-menus.update', $subMenu) }}">
        @csrf
        @method('PUT')
        <div class="grid grid-2">
            <div>
                <label for="menu_id">Parent menu</label>
                <select id="menu_id" name="menu_id" required>
                    <option value="">— Select menu —</option>
                    @foreach ($menus as $menu)
                    <option value="{{ $menu->id }}" @selected(old('menu_id', $subMenu->menu_id) == $menu->id)>{{ $menu->label }}</option>
                    @endforeach
                </select>
                @error('menu_id') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="label">Sub menu label</label>
                <input id="label" name="label" value="{{ old('label', $subMenu->label) }}" required>
                @error('label') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="url">Sub menu URL</label>
                <input id="url" name="url" value="{{ old('url', $subMenu->url) }}" required>
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
        </div>
        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit">Update sub-menu</button>
        </div>
    </form>
</div>
@endsection