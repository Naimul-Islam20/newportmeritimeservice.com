@extends('layouts.admin', ['title' => 'Users'])

@section('content')
<div class="header">
    <h1>Users</h1>
    @can('create', \App\Models\User::class)
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Create User</a>
    @endcan
</div>

<div class="card">
    <div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td>{{ $user->created_at?->format('Y-m-d H:i') }}</td>
                <td>
                    <div class="action-group">
                        @can('update', $user)
                        <a class="btn btn-muted" href="{{ route('admin.users.edit', $user) }}">Edit</a>
                        @endcan
                        @can('delete', $user)
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit" onclick="return confirm('Delete this user?')">Delete</button>
                        </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">No users found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div style="margin-top: 12px;">{{ $users->links() }}</div>
</div>
@endsection