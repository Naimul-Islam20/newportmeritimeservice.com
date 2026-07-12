@extends('layouts.admin', ['title' => 'Newsletter registrations'])

@section('content')
<div class="header">
    <div>
        <h1>Newsletter registrations</h1>
        <p style="margin:6px 0 0;color:#64748b;font-size:14px;">Email sign-ups from the footer newsletter form.</p>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Email</th>
                <th>Status</th>
                <th>Registered</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($subscriptions as $subscription)
            <tr>
                <td>{{ $subscription->email }}</td>
                <td>
                    <span class="status {{ $subscription->status === 'unread' ? 'status-unread' : 'status-read' }}">
                        {{ $subscription->status }}
                    </span>
                </td>
                <td>{{ $subscription->created_at?->format('Y-m-d H:i') }}</td>
                <td>
                    <div class="action-group">
                        <a class="btn btn-muted" href="{{ route('admin.newsletter-subscriptions.show', $subscription) }}">View</a>
                        <form method="POST" action="{{ route('admin.newsletter-subscriptions.destroy', $subscription) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit" onclick="return confirm('Delete this newsletter registration?')">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4">No newsletter registrations yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    <div style="margin-top: 12px;">{{ $subscriptions->links() }}</div>
</div>
@endsection
