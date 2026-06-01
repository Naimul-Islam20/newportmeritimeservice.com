@extends('layouts.admin', ['title' => 'Quality Certificates'])

@section('content')
<div class="header">
    <h1>Quality Certificates &amp; Memberships</h1>
    <a href="{{ route('quality-certificates') }}" class="btn btn-muted" target="_blank" rel="noopener">View public page</a>
</div>

@if (session('status'))
    <div class="card" style="margin-bottom: 16px; background: #ecfdf5; border-color: #a7f3d0;">{{ session('status') }}</div>
@endif

<div class="card" style="margin-bottom: 16px;">
    <h2 style="margin: 0 0 12px 0; font-size: 16px;">Page settings</h2>
    @can('update', $page)
    <form method="POST" action="{{ route('admin.quality-certificates.page.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid grid-2">
            <div>
                <label for="hero_title">Hero title</label>
                <input id="hero_title" name="hero_title" value="{{ old('hero_title', $page->hero_title) }}" required>
                @error('hero_title') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="page_intro">Page intro (below hero)</label>
                <input id="page_intro" name="page_intro" value="{{ old('page_intro', $page->page_intro) }}">
                @error('page_intro') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="meta_description">Meta description</label>
                <input id="meta_description" name="meta_description" value="{{ old('meta_description', $page->meta_description) }}">
                @error('meta_description') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="hero_background_file">Hero background image</label>
                @if ($page->heroBackgroundUrl() !== '')
                    <div style="margin-bottom:8px;">
                        <img src="{{ $page->heroBackgroundUrl() }}" alt="" style="max-width:320px; max-height:120px; object-fit:cover; border-radius:8px;">
                    </div>
                @endif
                <input id="hero_background_file" name="hero_background_file" type="file" accept="image/jpeg,image/png,image/webp,image/gif">
                <label style="display:flex; align-items:center; gap:6px; margin-top:8px; font-size:13px;">
                    <input type="checkbox" name="remove_hero_background" value="1"> Remove current hero image
                </label>
                @error('hero_background_file') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label style="display:flex; align-items:center; gap:8px; margin-top:28px;">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $page->is_active))> Page published
                </label>
            </div>
        </div>
        <div style="margin-top:14px;">
            <button type="submit" class="btn btn-primary">Save page settings</button>
        </div>
    </form>
    @endcan
</div>

<div class="card" style="margin-bottom: 16px;">
    <h2 style="margin: 0 0 12px 0; font-size: 16px;">Sections</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Layout</th>
                    <th>Certificates</th>
                    <th>Sort</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($groups as $group)
                <tr>
                    <td><strong>{{ $group->title }}</strong><br><code style="font-size:11px;">#{{ $group->slug }}</code></td>
                    <td>{{ $group->layout === 'stack' ? 'Stack (title + image)' : 'Grid' }}</td>
                    <td>{{ $group->certificates->count() }}</td>
                    <td>{{ $group->sort_order }}</td>
                    <td>{{ $group->is_active ? 'Active' : 'Hidden' }}</td>
                    <td class="actions-cell">
                        @can('update', $group)
                        <a href="{{ route('admin.quality-certificates.groups.edit', $group) }}" class="btn btn-muted">Manage</a>
                        @endcan
                        @can('delete', $group)
                        <form method="POST" action="{{ route('admin.quality-certificates.groups.destroy', $group) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit" onclick="return confirm('Remove this section and all certificates in it?')">Delete</button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">No sections yet. Add one below.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@can('create', \App\Models\CertificateGroup::class)
<div class="card">
    <h2 style="margin: 0 0 12px 0; font-size: 16px;">Add section</h2>
    <form method="POST" action="{{ route('admin.quality-certificates.groups.store') }}">
        @csrf
        <div class="grid grid-2">
            <div>
                <label for="title">Section title</label>
                <input id="title" name="title" value="{{ old('title') }}" placeholder="OUR TURKEY QUALITY CERTIFICATES" required>
                @error('title') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="slug">URL anchor (optional)</label>
                <input id="slug" name="slug" value="{{ old('slug') }}" placeholder="turkey-quality-certificates">
                @error('slug') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="intro">Intro text (optional)</label>
                <input id="intro" name="intro" value="{{ old('intro') }}" placeholder="Click on the image to view the certificates.">
                @error('intro') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="layout">Layout</label>
                <select id="layout" name="layout">
                    <option value="grid" @selected(old('layout', 'grid') === 'grid')>Grid — multiple logos in a row</option>
                    <option value="stack" @selected(old('layout') === 'stack')>Stack — each certificate with its own heading</option>
                </select>
                @error('layout') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="sort_order">Sort order</label>
                <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $nextGroupSort) }}">
                @error('sort_order') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label style="display:flex; align-items:center; gap:8px; margin-top:28px;">
                    <input type="checkbox" name="show_divider_before" value="1" @checked(old('show_divider_before'))> Show divider line before section
                </label>
            </div>
            <div>
                <label style="display:flex; align-items:center; gap:8px; margin-top:28px;">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))> Active
                </label>
            </div>
        </div>
        <div style="margin-top:14px;">
            <button type="submit" class="btn btn-primary">Add section</button>
        </div>
    </form>
</div>
@endcan
@endsection
