@extends('layouts.admin', ['title' => 'Contact Form'])

@section('content')
<div class="header">
    <div>
        <h1>Contact Form</h1>
        <p style="margin:6px 0 0;color:#64748b;font-size:14px;">Submissions from the public contact page.</p>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Submitted</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($messages as $message)
            <tr>
                <td>{{ $message->full_name }}</td>
                <td>{{ $message->email }}</td>
                <td>{{ $message->phone ?? '—' }}</td>
                <td>
                    <span class="status {{ $message->status === 'unread' ? 'status-unread' : 'status-read' }}">
                        {{ $message->status }}
                    </span>
                </td>
                <td>{{ $message->created_at?->format('Y-m-d H:i') }}</td>
                <td>
                    <div class="action-group">
                        <a class="btn btn-muted" href="{{ route('admin.contact-messages.show', $message) }}">View</a>
                        <form method="POST" action="{{ route('admin.contact-messages.destroy', $message) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit" onclick="return confirm('Delete this message?')">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">No messages found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    <div style="margin-top: 12px;">{{ $messages->links() }}</div>
</div>
@endsection