@extends('layouts.admin', ['title' => 'Sub menus'])

@section('content')
<div class="header">
    <h1>Sub menus</h1>
    @can('create', \App\Models\SubMenu::class)
    <a href="{{ route('admin.sub-menus.create') }}" class="btn btn-primary">Create sub-menu</a>
    @endcan
</div>

<div class="card">
    <div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Menu</th>
                <th>Label</th>
                <th>URL</th>
                <th>Sort</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($subMenus as $sub)
            <tr>
                <td>{{ $sub->menu?->label ?? '—' }}</td>
                <td>{{ $sub->label }}</td>
                <td><code style="font-size:12px;">{{ \Illuminate\Support\Str::limit($sub->url, 48) }}</code></td>
                <td>{{ $sub->sort_order }}</td>
                <td>{{ $sub->is_active ? 'Yes' : 'No' }}</td>
                <td>
                    <div class="action-group">
                        @can('update', $sub)
                        <a class="btn btn-muted" href="{{ route('admin.sub-menus.edit', $sub) }}">Edit</a>
                        @endcan
                        @can('delete', $sub)
                        <form method="POST" action="{{ route('admin.sub-menus.destroy', $sub) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit" onclick="return confirm('Delete this sub-menu?')">Delete</button>
                        </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">No sub-menus yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div style="margin-top: 12px;">{{ $subMenus->links() }}</div>
</div>
@endsection

