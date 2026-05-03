@extends('layouts.admin', ['title' => 'Newsletter'])

@section('content')
<div class="header">
    <h1>Newsletter</h1>
    <a href="{{ route('admin.newsletters.create') }}" class="btn btn-primary">Create Newsletter</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Date</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($newsletters as $newsletter)
                    <tr>
                        <td>{{ $newsletter->id }}</td>
                        <td style="max-width: 260px; white-space: normal;">
                            {{ \Illuminate\Support\Str::limit($newsletter->title, 45) }}
                        </td>
                        <td>{{ $newsletter->category?->name ?? '-' }}</td>
                        <td>{{ $newsletter->published_at?->format('Y-m-d') ?? $newsletter->created_at?->format('Y-m-d') }}</td>
                        <td>
                            <img
                                src="{{ asset($newsletter->image_path) }}"
                                alt="{{ $newsletter->title }}"
                                style="width:72px;height:48px;object-fit:cover;border-radius:6px;border:1px solid #e2e8f0;">
                        </td>
                        <td class="actions-cell">
                            <div class="action-group">
                                <a class="btn btn-muted" href="{{ route('admin.newsletters.show', $newsletter) }}">View</a>
                                <a class="btn btn-primary" href="{{ route('admin.newsletters.edit', $newsletter) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.newsletters.destroy', $newsletter) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit" onclick="return confirm('Delete this newsletter?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No newsletter items found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 12px;">{{ $newsletters->links() }}</div>
</div>
@endsection
