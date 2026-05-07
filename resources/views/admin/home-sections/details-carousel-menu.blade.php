@extends('layouts.admin', ['title' => 'Carousel details'])

@section('content')
<div class="header">
    <h1>Carousel details</h1>
    <a class="btn btn-muted" href="{{ route('admin.home-sections.create') }}">Back</a>
</div>

<div class="card">
    <div style="margin-bottom: 10px; color:#64748b; font-size:13px;">
        Carousel type: <strong>{{ $variant ?: '—' }}</strong> (static UI)
    </div>

    <form method="POST" action="{{ route('admin.home-sections.details.store') }}">
        @csrf
        <div class="grid grid-2">
            <div style="grid-column: 1 / -1;">
                <label for="menu_id">Menu</label>
                <select id="menu_id" name="menu_id" required>
                    <option value="">— Select —</option>
                    @forelse ($menus as $menu)
                        <option value="{{ $menu->id }}" @selected((string) old('menu_id') === (string) $menu->id)>
                            {{ $menu->label }}
                        </option>
                    @empty
                        <option value="" disabled>No menus with sub menus found</option>
                    @endforelse
                </select>
                @error('menu_id') <div class="error">{{ $message }}</div> @enderror
                <div style="color:#64748b; font-size:12px; margin-top:6px;">
                    Dropdown shows only header menus that have sub menus.
                </div>
            </div>

            <div>
                <label for="mini_title">Mini title</label>
                <input id="mini_title" name="mini_title" value="{{ old('mini_title') }}" placeholder="Optional">
                @error('mini_title') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="title">Title</label>
                <input id="title" name="title" value="{{ old('title') }}" required>
                @error('title') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit">Save</button>
            <a class="btn btn-muted" href="{{ route('admin.home-sections.index') }}">Cancel</a>
        </div>
    </form>
</div>
@endsection

