@extends('layouts.admin', ['title' => 'Edit Home Section'])

@section('content')
<div class="header">
    <h1>Edit home section</h1>
    <a class="btn btn-muted" href="{{ route('admin.home-sections.index') }}">Back</a>
</div>

<div class="card">
    <div style="margin-bottom: 10px; color:#64748b; font-size:13px;">
        Type: <strong>{{ $section->block_type }}</strong> — Variant: <strong>{{ $section->variant }}</strong>
    </div>

    @php
        $linkedMenu = $section->menu ?? $menus->firstWhere('id', $section->menu_id);
        $manageCardsUrl = match ($linkedMenu?->normalizedPath()) {
            '/our-services' => route('admin.our-services-sub-menus.index'),
            '/ship-supply' => route('admin.ship-supply-sub-menus.index'),
            default => null,
        };
    @endphp

    @if ($section->variant === 'simple' && $manageCardsUrl)
        <p style="margin: 0 0 14px; padding: 12px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; color: #475569; font-size: 13px; line-height: 1.55;">
            Carousel cards are managed from the linked menu’s sub-items (label, icon, description, URL, sort order).
            <a href="{{ $manageCardsUrl }}" style="font-weight: 600;">Manage carousel cards →</a>
        </p>
    @endif

    <form method="POST" action="{{ route('admin.home-sections.update', $section) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-2">
            <div style="grid-column: 1 / -1;">
                <label for="menu_id">Menu</label>
                <select id="menu_id" name="menu_id" required>
                    <option value="">— Select —</option>
                    @foreach ($menus as $menu)
                        <option value="{{ $menu->id }}" @selected((string) old('menu_id', $section->menu_id) === (string) $menu->id)>
                            {{ $menu->label }}
                        </option>
                    @endforeach
                </select>
                @error('menu_id') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="mini_title">Mini title</label>
                <input id="mini_title" name="mini_title" value="{{ old('mini_title', $section->mini_title) }}" placeholder="Optional">
                @error('mini_title') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="title">Title</label>
                <input id="title" name="title" value="{{ old('title', $section->title) }}" required>
                @error('title') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="sort_order">Sort order</label>
                <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $section->sort_order) }}">
                @error('sort_order') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="is_active">Status</label>
                <select id="is_active" name="is_active">
                    <option value="1" @selected(old('is_active', $section->is_active) == 1)>Active</option>
                    <option value="0" @selected(old('is_active', $section->is_active) == 0)>Inactive</option>
                </select>
                @error('is_active') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit">Update</button>
        </div>
    </form>
</div>
@endsection

