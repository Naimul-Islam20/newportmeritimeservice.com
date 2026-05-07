@extends('layouts.admin', ['title' => 'Hero section'])

@section('content')
<div class="header">
    <h1>Hero section</h1>
    <a href="{{ route('admin.home-sections.index') }}" class="btn btn-muted">Home sections</a>
</div>

<div class="card" style="margin-bottom: 16px;">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Button</th>
                    <th>Sort</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($slides as $slide)
                <tr>
                    <td class="hero-thumb-cell">
                        @if ($slide->imagePublicUrl() !== '')
                        <img src="{{ $slide->imagePublicUrl() }}" alt="" width="72" height="48" style="object-fit: cover; border-radius: 4px; display: block;">
                        @elseif ($slide->image_path)
                        <span style="font-size:11px;color:#b45309;">File missing — re-upload</span>
                        @else
                        —
                        @endif
                    </td>
                    <td style="white-space: normal;"><strong>{{ $slide->title }}</strong></td>
                    <td style="white-space: normal; max-width: 280px;">{{ \Illuminate\Support\Str::limit($slide->description ?? '—', 120) }}</td>
                    <td style="white-space: normal;">
                        @if ($slide->button_label || $slide->button_url)
                        <div><strong>{{ $slide->button_label ?: '—' }}</strong></div>
                        @if ($slide->button_url)
                        <code style="font-size: 11px;">{{ \Illuminate\Support\Str::limit($slide->button_url, 40) }}</code>
                        @endif
                        @else
                        —
                        @endif
                    </td>
                    <td>{{ $slide->sort_order }}</td>
                    <td class="actions-cell">
                        @can('delete', $slide)
                        <form method="POST" action="{{ route('admin.hero-slides.destroy', $slide) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit" onclick="return confirm('Remove this slide?')">Delete</button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">No hero slides yet. Add one using the form below.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <h2 style="margin: 0 0 12px 0; font-size: 16px;">Add slide</h2>
    @can('create', \App\Models\HeroSlide::class)
    <form method="POST" action="{{ route('admin.hero-slides.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-2">
            <div>
                <label for="title">Title</label>
                <input id="title" name="title" value="{{ old('title') }}" required>
                @error('title') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="sort_order">Sort order</label>
                <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $nextSortOrder ?? 0) }}">
                @error('sort_order') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4" placeholder="Optional">{{ old('description') }}</textarea>
                @error('description') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="button_label">Button label</label>
                <input id="button_label" name="button_label" value="{{ old('button_label') }}" placeholder="e.g. Learn more">
                @error('button_label') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="button_url">Button URL</label>
                <input id="button_url" name="button_url" value="{{ old('button_url') }}" placeholder="/our-services or https://…">
                @error('button_url') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="image">Image</label>
                <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp,image/gif" required>
                @error('image') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>
        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit">Save slide</button>
        </div>
    </form>
    @else
    <p style="color:#64748b;">You do not have permission to add slides.</p>
    @endcan
</div>
@endsection
