@extends('layouts.admin', ['title' => 'Free Consultation Requests'])

@section('content')
<div class="header">
    <h1>Free Consultation Requests</h1>
</div>

<div class="card">
    <div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Company Name</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Status</th>
                <th>Submitted</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($expertSessions as $session)
            <tr>
                <td>{{ $session->name }}</td>
                <td>{{ $session->company_name }}</td>
                <td>{{ $session->mobile }}</td>
                <td>{{ $session->email }}</td>
                <td>
                    <span class="status {{ $session->status === 'new' ? 'status-unread' : 'status-review' }}">
                        {{ $session->status }}
                    </span>
                </td>
                <td>{{ $session->created_at?->format('Y-m-d H:i') }}</td>
                <td>
                    <div class="action-group">
                        <a class="btn btn-muted" href="{{ route('admin.expert-sessions.show', $session) }}">View</a>
                        <form method="POST" action="{{ route('admin.expert-sessions.destroy', $session) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit" onclick="return confirm('Delete this request?')">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">No consultation requests found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div style="margin-top: 12px;">{{ $expertSessions->links() }}</div>
</div>
@endsection
