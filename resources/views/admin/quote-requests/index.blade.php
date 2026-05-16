@extends('layouts.admin', ['title' => 'Get a quote'])

@section('content')
<div class="header">
    <div>
        <h1>Get a quote</h1>
        <p style="margin:6px 0 0;color:#64748b;font-size:14px;">Submissions from the public “Get a quote” form.</p>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Full name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Submitted</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($requests as $req)
            <tr>
                <td>{{ $req->full_name }}</td>
                <td>{{ $req->email }}</td>
                <td>{{ $req->phone ?? '—' }}</td>
                <td>
                    <span class="status {{ $req->status === 'unread' ? 'status-unread' : 'status-read' }}">
                        {{ $req->status }}
                    </span>
                </td>
                <td>{{ $req->created_at?->format('Y-m-d H:i') }}</td>
                <td>
                    <div class="action-group">
                        <a class="btn btn-muted" href="{{ route('admin.quote-requests.show', $req) }}">View</a>
                        <form method="POST" action="{{ route('admin.quote-requests.destroy', $req) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit" onclick="return confirm('Delete this quote request?')">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">No quote requests yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    <div style="margin-top: 12px;">{{ $requests->links() }}</div>
</div>
@endsection
