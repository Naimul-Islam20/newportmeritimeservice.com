@extends('layouts.admin', ['title' => 'Products page'])

@section('content')
<div class="header">
    <h1>Products page</h1>
    <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <a class="btn btn-muted" href="{{ route('admin.ship-supply-sub-menus.index') }}">Back to sub menus</a>
        <a class="btn btn-muted" href="{{ route('ship-supply') }}" target="_blank" rel="noopener">View on site</a>
    </div>
</div>

<p style="margin:0 0 16px;font-size:14px;color:#5a6578;">
  Manage the public <strong>/ship-supply</strong> (Products) landing page hero banner — the large background image behind the page title.
</p>

<div class="card">
    <form method="POST" action="{{ route('admin.ship-supply-landing.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-2">
            <div style="grid-column: 1 / -1;">
                <label for="cover_image">Hero background photo</label>
                @if ($menu->cover_image_path)
                    <div style="margin-bottom:10px;">
                        <img
                            src="{{ $menu->coverImageUrl() }}"
                            alt="Current products page background"
                            style="max-height:180px;max-width:100%;object-fit:cover;border:1px solid #e2e8f0;border-radius:8px;"
                        >
                        <label style="display:flex;gap:8px;margin-top:10px;font-size:13px;">
                            <input type="checkbox" name="remove_cover_image" value="1" @checked(old('remove_cover_image'))>
                            Remove background photo (use default)
                        </label>
                    </div>
                @endif
                <input id="cover_image" name="cover_image" type="file" accept="image/png,image/jpeg,image/webp,image/svg+xml">
                <div style="color:#64748b;font-size:12px;margin-top:6px;">
                    Recommended: wide landscape image (1920×600 or larger). Max 5MB. JPG, PNG, or WebP.
                </div>
                @error('cover_image') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit">Save background</button>
        </div>
    </form>
</div>
@endsection
