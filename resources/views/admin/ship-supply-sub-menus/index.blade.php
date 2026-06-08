@extends('layouts.admin', ['title' => 'Ship Supply — menu'])

@section('content')
<div class="header">
    <h1>Ship Supply — sub menus</h1>
    @can('create', \App\Models\SubMenu::class)
    <a href="{{ route('admin.ship-supply-sub-menus.create') }}" class="btn btn-primary">Create</a>
    @endcan
</div>

<div class="card">
    <p style="margin: 0 0 14px 0; color: #64748b; font-size: 13px;">
        Items listed here appear in the site header under <strong>{{ $menu->label }}</strong>.
        Active items show on the frontend; inactive items are hidden. Sort order controls the dropdown sequence.
    </p>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Label</th>
                    <th>URL</th>
                    <th>Sort</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($subMenus as $sub)
                <tr>
                    <td>{{ $sub->id }}</td>
                    <td>{{ $sub->label }}</td>
                    <td><code style="font-size:12px;">{{ \Illuminate\Support\Str::limit($sub->url, 56) }}</code></td>
                    <td>{{ $sub->sort_order }}</td>
                    <td>
                        @if ($sub->is_active)
                            <span style="color:#15803d;font-weight:600;">Active</span>
                        @else
                            <span style="color:#b45309;font-weight:600;">Inactive</span>
                        @endif
                    </td>
                    <td class="actions-cell">
                        @can('update', $sub)
                        <form method="POST" action="{{ route('admin.ship-supply-sub-menus.toggle-active', $sub) }}" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-muted" type="submit">
                                {{ $sub->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        <a class="btn btn-muted" href="{{ route('admin.ship-supply-sub-menus.edit', $sub) }}">Edit</a>
                        @if (! $sub->isFormPageLink())
                        <a class="btn btn-primary" href="{{ $sub->adminSidebarHref() }}">Page content</a>
                        @endif
                        @endcan
                        @can('delete', $sub)
                        <form method="POST" action="{{ route('admin.ship-supply-sub-menus.destroy', $sub) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit" onclick="return confirm('Delete “{{ $sub->label }}” from the menu?')">Delete</button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">No sub-menus yet. <a href="{{ route('admin.ship-supply-sub-menus.create') }}">Create one</a>.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
