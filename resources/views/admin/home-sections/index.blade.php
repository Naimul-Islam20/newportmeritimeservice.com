@extends('layouts.admin', ['title' => 'Home Sections'])

@section('content')
<div class="header">
    <h1>Home page sections</h1>
    <div style="display: inline-flex; gap: 8px; flex-wrap: wrap; align-items: center;">
        <a href="{{ route('admin.hero-slides.index') }}" class="btn btn-muted">Hero section</a>
        <a href="{{ route('admin.home-sections.service-area') }}" class="btn btn-muted">Service area</a>
        <a href="{{ route('admin.home-sections.visual-frames') }}" class="btn btn-muted">Visual showcase</a>
        <a href="{{ route('admin.home-sections.create') }}" class="btn btn-primary">Create</a>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Section</th>
                    <th>Type</th>
                    <th>Variant</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sections as $section)
                @php
                    $sectionTypeLabel = match (true) {
                        $section->block_type === 'carousel' => 'Carousel ('.($section->variant ?? '').')',
                        $section->block_type === 'image' => 'Image',
                        $section->block_type === 'text_input' => 'Text & points',
                        $section->block_type === 'logo_carousel' => 'Certificates / logos',
                        $section->block_type === 'two_column' && $section->two_column_mode === 'image_details' => 'Image and details',
                        $section->block_type === 'two_column' && $section->two_column_mode === 'split_cta' => 'Text, image & CTAs',
                        $section->block_type === 'two_column' && $section->two_column_mode === 'both_sides_details' => '2 side details',
                        default => $section->block_type,
                    };
                @endphp
                <tr>
                    <td>{{ $section->id }}</td>
                    <td>{{ $section->title ?? '—' }}</td>
                    <td>{{ $sectionTypeLabel }}</td>
                    <td>{{ $section->variant ?? '—' }}</td>
                    <td>{{ $section->is_active ? 'Active' : 'Inactive' }}</td>
                    <td class="actions-cell">
                        <a class="btn btn-muted" href="{{ route('admin.home-sections.edit', $section) }}">Edit</a>
                        @can('delete', $section)
                        <form method="POST" action="{{ route('admin.home-sections.destroy', $section) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit" onclick="return confirm('Remove this home section? This cannot be undone.')">Delete</button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">No sections yet. <a href="{{ route('admin.home-sections.create') }}">Create one</a>.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 10px; color:#64748b; font-size:13px;">
        Sections: carousel, image, image &amp; details, 2 side details, text &amp; points.
    </div>
</div>
@endsection

