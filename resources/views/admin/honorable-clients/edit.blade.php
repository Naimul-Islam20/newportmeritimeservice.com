@extends('layouts.admin', ['title' => 'Edit Honorable Client'])

@section('content')
<div class="header">
    <h1>Edit client</h1>
    <a class="btn btn-muted" href="{{ route('admin.honorable-clients.index') }}">Back to list</a>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.honorable-clients.update', $client) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid grid-2">
            <div style="grid-column: 1 / -1;">
                <label for="name">Company name</label>
                <input id="name" name="name" value="{{ old('name', $client->name) }}" required>
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="logo_file">Logo image</label>
                <input id="logo_file" name="logo_file" type="file" accept="image/*">
                @error('logo_file') <div class="error">{{ $message }}</div> @enderror
                @if ($client->hasLogo())
                    <div style="margin-top:10px;padding:12px;background:#f8fafc;border-radius:8px;">
                        <img src="{{ $client->logoPublicUrl() }}" alt="{{ $client->name }}" style="max-height:72px;max-width:220px;object-fit:contain;">
                    </div>
                    <label style="display:flex;align-items:center;gap:8px;margin-top:8px;">
                        <input type="checkbox" name="remove_logo" value="1"> Remove current logo
                    </label>
                @endif
            </div>
            <div>
                <label for="sort_order">Sort order</label>
                <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $client->sort_order) }}">
                @error('sort_order') <div class="error">{{ $message }}</div> @enderror
                <label style="display:flex;align-items:center;gap:8px;margin-top:20px;">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $client->is_active))> Active on site
                </label>
            </div>
        </div>
        <div style="margin-top:14px;">
            <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
    </form>
</div>
@endsection
