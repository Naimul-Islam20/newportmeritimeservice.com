@extends('layouts.admin', ['title' => $category->label])

@section('content')
<div class="header">
    <h1>{{ $category->label }}</h1>
    <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
        <a class="btn btn-primary" href="{{ $createUrl }}">{{ $category->categoryCreateButtonLabel() }}</a>
        <a class="btn btn-muted" href="{{ $pageSectionsUrl }}">Page sections</a>
        <a class="btn btn-muted" href="{{ route('admin.sub-menus.edit', $category) }}">Menu details</a>
        <a class="btn btn-muted" href="{{ route('admin.sub-menus.index') }}">Back</a>
    </div>
</div>

<div class="card" style="margin-bottom:12px; color:#64748b; font-size:13px;">
    Manage <strong>{{ $category->label }}</strong> only. URL prefix: <code>{{ $category->categoryBasePath() }}/your-slug</code>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>URL</th>
                    <th>Date</th>
                    <th>Active</th>
                    <th class="actions-cell">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td>{{ $item->label }}</td>
                        <td><code style="font-size:12px;">{{ \Illuminate\Support\Str::limit($item->url, 56) }}</code></td>
                        <td>{{ $item->published_at?->format('d M Y') ?? '—' }}</td>
                        <td>{{ $item->is_active ? 'Yes' : 'No' }}</td>
                        <td class="actions-cell">
                            <div class="action-group">
                                <a class="btn btn-muted" href="{{ route('admin.sub-menus.edit', $item) }}">Edit</a>
                                @can('delete', $item)
                                <form method="POST" action="{{ route('admin.sub-menus.destroy', $item) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit" onclick="return confirm('Delete this item?')">Delete</button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No {{ strtolower($category->label) }} yet. Click <strong>{{ $category->categoryCreateButtonLabel() }}</strong>.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
