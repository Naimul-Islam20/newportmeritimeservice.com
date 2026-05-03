@extends('layouts.admin', ['title' => 'Quote Requests'])

@section('content')
    <div class="header">
        <h1>Quote Requests</h1>
    </div>

    <div class="card">
        <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Company</th>
                    <th>Employees</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($quoteRequests as $request)
                    <tr>
                        <td>{{ $request->name }}</td>
                        <td>{{ $request->company_name }}</td>
                        <td>{{ $request->employee_count ?? '-' }}</td>
                        <td>{{ $request->email }}</td>
                        <td>
                            <span class="status {{ $request->status === 'new' ? 'status-unread' : 'status-review' }}">
                                {{ $request->status }}
                            </span>
                        </td>
                        <td>{{ $request->created_at?->format('Y-m-d H:i') }}</td>
                        <td>
                            <div class="action-group">
                                <a class="btn btn-muted" href="{{ route('admin.quote-requests.show', $request) }}">View</a>
                                <form method="POST" action="{{ route('admin.quote-requests.destroy', $request) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit" onclick="return confirm('Delete this quote request?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7">No quote requests found.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
        <div style="margin-top: 12px;">{{ $quoteRequests->links() }}</div>
    </div>
@endsection
