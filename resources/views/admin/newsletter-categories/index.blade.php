@extends('layouts.admin', ['title' => 'Newsletter Categories'])

@section('content')
<div class="header">
    <h1>Newsletter Categories</h1>
    <a href="{{ route('admin.newsletter-categories.create') }}" class="btn btn-primary">Create Category</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->created_at?->format('Y-m-d H:i') }}</td>
                        <td class="actions-cell">
                            <div class="action-group">
                                <a class="btn btn-primary" href="{{ route('admin.newsletter-categories.edit', $category) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.newsletter-categories.destroy', $category) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit" onclick="return confirm('Delete this category?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No newsletter categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 12px;">{{ $categories->links() }}</div>
</div>
@endsection
