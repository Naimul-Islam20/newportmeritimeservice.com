@extends('layouts.admin', ['title' => 'Quote request'])

@section('content')
<div class="header">
    <h1>Quote request</h1>
    <a href="{{ route('admin.quote-requests.index') }}" class="btn btn-muted">Back</a>
</div>

<div class="card grid">
    <div><strong>Full name:</strong> {{ $quoteRequest->full_name }}</div>
    <div><strong>Email:</strong> {{ $quoteRequest->email }}</div>
    <div><strong>Phone:</strong> {{ $quoteRequest->phone ?? '—' }}</div>
    <div><strong>Company:</strong> {{ $quoteRequest->company ?: '—' }}</div>
    <div><strong>Vessel / reference:</strong> {{ $quoteRequest->vessel_or_reference ?: '—' }}</div>
    <div><strong>Timeline:</strong> {{ $quoteRequest->timeline ?: '—' }}</div>
    <div>
        <strong>Status:</strong>
        <span class="status {{ $quoteRequest->status === 'unread' ? 'status-unread' : 'status-read' }}">
            {{ $quoteRequest->status }}
        </span>
    </div>
    <div><strong>What you need quoted:</strong></div>
    <div style="white-space:pre-wrap;">{{ $quoteRequest->request_details }}</div>
</div>
@endsection
