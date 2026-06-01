@extends('layouts.admin', ['title' => 'Certificates — '.$group->title])

@section('content')
<div class="header">
    <h1>{{ $group->title }}</h1>
    <a href="{{ route('admin.quality-certificates.index') }}" class="btn btn-muted">All sections</a>
    <a href="{{ route('quality-certificates') }}#{{ $group->slug }}" class="btn btn-muted" target="_blank" rel="noopener">View on site</a>
</div>

@if (session('status'))
    <div class="card" style="margin-bottom: 16px; background: #ecfdf5; border-color: #a7f3d0;">{{ session('status') }}</div>
@endif

<div class="card" style="margin-bottom: 16px;">
    <h2 style="margin: 0 0 12px 0; font-size: 16px;">Section settings</h2>
    @can('update', $group)
    <form method="POST" action="{{ route('admin.quality-certificates.groups.update', $group) }}">
        @csrf
        @method('PUT')
        <div class="grid grid-2">
            <div>
                <label for="title">Section title</label>
                <input id="title" name="title" value="{{ old('title', $group->title) }}" required>
                @error('title') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="slug">URL anchor</label>
                <input id="slug" name="slug" value="{{ old('slug', $group->slug) }}">
                @error('slug') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="intro">Intro text</label>
                <input id="intro" name="intro" value="{{ old('intro', $group->intro) }}">
                @error('intro') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="layout">Layout</label>
                <select id="layout" name="layout">
                    <option value="grid" @selected(old('layout', $group->layout) === 'grid')>Grid</option>
                    <option value="stack" @selected(old('layout', $group->layout) === 'stack')>Stack</option>
                </select>
            </div>
            <div>
                <label for="sort_order">Sort order</label>
                <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $group->sort_order) }}">
            </div>
            <div>
                <label style="display:flex; align-items:center; gap:8px; margin-top:28px;">
                    <input type="checkbox" name="show_divider_before" value="1" @checked(old('show_divider_before', $group->show_divider_before))> Divider before section
                </label>
            </div>
            <div>
                <label style="display:flex; align-items:center; gap:8px; margin-top:28px;">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $group->is_active))> Active
                </label>
            </div>
        </div>
        <div style="margin-top:14px;">
            <button type="submit" class="btn btn-primary">Save section</button>
        </div>
    </form>
    @endcan
</div>

<div class="card" style="margin-bottom: 16px;">
    <h2 style="margin: 0 0 12px 0; font-size: 16px;">Certificates in this section</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Preview</th>
                    <th>Title</th>
                    <th>PDF</th>
                    <th>Sort</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($group->certificates as $cert)
                <tr>
                    <td>
                        @if ($cert->imagePublicUrl() !== '')
                            <img src="{{ $cert->imagePublicUrl() }}" alt="" width="72" height="52" style="object-fit:contain; background:#f3f4f6; border-radius:4px; padding:4px;">
                        @else
                            —
                        @endif
                    </td>
                    <td><strong>{{ $cert->title }}</strong></td>
                    <td>
                        @if ($cert->hasViewablePdf())
                            <a href="{{ $cert->pdfPublicUrl() }}" target="_blank" rel="noopener">View PDF</a>
                        @else
                            <span style="color:#b45309;">Missing</span>
                        @endif
                    </td>
                    <td>{{ $cert->sort_order }}</td>
                    <td>{{ $cert->is_active ? 'Active' : 'Hidden' }}</td>
                    <td class="actions-cell">
                        @can('update', $cert)
                        <a href="{{ route('admin.quality-certificates.groups.edit', $group) }}#cert-{{ $cert->id }}" class="btn btn-muted">Edit below</a>
                        @endcan
                        @can('delete', $cert)
                        <form method="POST" action="{{ route('admin.quality-certificates.groups.certificates.destroy', [$group, $cert]) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit" onclick="return confirm('Remove this certificate?')">Delete</button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">No certificates in this section yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@can('create', \App\Models\QualityCertificate::class)
<div class="card" style="margin-bottom: 16px;">
    <h2 style="margin: 0 0 12px 0; font-size: 16px;">Add certificate</h2>
    <form method="POST" action="{{ route('admin.quality-certificates.groups.certificates.store', $group) }}" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-2">
            <div>
                <label for="new_title">Title</label>
                <input id="new_title" name="title" value="{{ old('title') }}" required>
                @error('title') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="new_sort_order">Sort order</label>
                <input id="new_sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $nextCertSort) }}">
            </div>
            <div>
                <label for="new_image">Thumbnail image (logo / preview)</label>
                <input id="new_image" name="image" type="file" accept="image/jpeg,image/png,image/webp,image/gif">
                @error('image') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="new_pdf">Certificate PDF</label>
                <input id="new_pdf" name="pdf" type="file" accept="application/pdf" required>
                @error('pdf') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label style="display:flex; align-items:center; gap:8px; margin-top:28px;">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))> Active
                </label>
            </div>
        </div>
        <div style="margin-top:14px;">
            <button type="submit" class="btn btn-primary">Add certificate</button>
        </div>
    </form>
</div>
@endcan

@foreach ($group->certificates as $cert)
    @can('update', $cert)
    <div class="card" id="cert-{{ $cert->id }}" style="margin-bottom: 16px;">
        <h2 style="margin: 0 0 12px 0; font-size: 16px;">Edit: {{ $cert->title }}</h2>
        <form method="POST" action="{{ route('admin.quality-certificates.groups.certificates.update', [$group, $cert]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-2">
                <div>
                    <label>Title</label>
                    <input name="title" value="{{ old('title', $cert->title) }}" required>
                </div>
                <div>
                    <label>Sort order</label>
                    <input name="sort_order" type="number" min="0" value="{{ old('sort_order', $cert->sort_order) }}">
                </div>
                <div>
                    <label>Replace thumbnail</label>
                    @if ($cert->imagePublicUrl() !== '')
                        <div style="margin-bottom:8px;"><img src="{{ $cert->imagePublicUrl() }}" alt="" style="max-width:140px; max-height:90px; object-fit:contain;"></div>
                    @endif
                    <input name="image" type="file" accept="image/jpeg,image/png,image/webp,image/gif">
                </div>
                <div>
                    <label>Replace PDF</label>
                    @if ($cert->hasViewablePdf())
                        <p style="font-size:13px; margin:0 0 8px;"><a href="{{ $cert->pdfPublicUrl() }}" target="_blank" rel="noopener">Current PDF</a></p>
                    @endif
                    <input name="pdf" type="file" accept="application/pdf">
                </div>
                <div>
                    <label style="display:flex; align-items:center; gap:8px; margin-top:28px;">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $cert->is_active))> Active
                    </label>
                </div>
            </div>
            <div style="margin-top:14px;">
                <button type="submit" class="btn btn-primary">Save certificate</button>
            </div>
        </form>
    </div>
    @endcan
@endforeach
@endsection
