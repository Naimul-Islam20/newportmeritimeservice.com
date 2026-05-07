@extends('layouts.admin', ['title' => 'Create menu'])

@section('content')
<div class="header">
    <h1>Create menu</h1>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.menus.store') }}">
        @csrf
        <div class="grid grid-2">
            <div>
                <label for="label">Label</label>
                <input id="label" name="label" value="{{ old('label') }}" required>
                @error('label') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="url">URL</label>
                <input id="url" name="url" value="{{ old('url') }}" placeholder="/about-us or https://…" required>
                @error('url') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="sort_order">Sort order</label>
                <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $nextSortOrder ?? 0) }}">
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
        </div>
        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit">Save menu</button>
        </div>
    </form>
</div>
@endsection
