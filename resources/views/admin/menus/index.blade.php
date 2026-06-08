@extends('layouts.admin', ['title' => 'Menus'])

@section('content')
<div class="header">
    <h1>Site header menus</h1>
    @can('create', \App\Models\Menu::class)
    <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">Create menu</a>
    @endcan
</div>

<div class="card">
    <p style="margin: 0 0 14px 0; color: #64748b; font-size: 13px;">
        Active menus appear in the site header. Inactive menus are hidden on the frontend until activated again.
    </p>
    <div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Label</th>
                <th>URL</th>
                <th>Sort</th>
                <th>Status</th>
                <th>Level</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($menus as $menu)
            <tr>
                <td><strong>{{ $menu->label }}</strong></td>
                <td><code style="font-size:12px;">{{ \Illuminate\Support\Str::limit($menu->url, 48) }}</code></td>
                <td>{{ $menu->sort_order }}</td>
                <td>
                    @if ($menu->is_active)
                        <span style="color:#15803d;font-weight:600;">Active</span>
                    @else
                        <span style="color:#b45309;font-weight:600;">Inactive</span>
                    @endif
                </td>
                <td>Top</td>
                <td>
                    <div class="action-group">
                        @can('update', $menu)
                        <form method="POST" action="{{ route('admin.menus.toggle-active', $menu) }}" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-muted" type="submit">
                                {{ $menu->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        @if (! $menu->isFormPageMenu())
                        <a class="btn btn-primary" href="{{ route('admin.menus.page-sections.index', $menu) }}">Sections</a>
                        @endif
                        <a class="btn btn-muted" href="{{ route('admin.menus.edit', $menu) }}">Details</a>
                        @endcan
                        @can('delete', $menu)
                        <form method="POST" action="{{ route('admin.menus.destroy', $menu) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit" onclick="return confirm('Delete this menu and its sub-menus?')">Delete</button>
                        </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">No menus yet. Create one or run the seeder.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection
