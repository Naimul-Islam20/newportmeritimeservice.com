@extends('layouts.admin', ['title' => 'Newsletter registration'])

@section('content')
<div class="header">
    <h1>Newsletter registration</h1>
    <a href="{{ route('admin.newsletter-subscriptions.index') }}" class="btn btn-muted">Back</a>
</div>

<div class="card grid">
    <div><strong>Email:</strong> {{ $subscription->email }}</div>
    <div>
        <strong>Status:</strong>
        <span class="status {{ $subscription->status === 'unread' ? 'status-unread' : 'status-read' }}">
            {{ $subscription->status }}
        </span>
    </div>
    <div><strong>Registered:</strong> {{ $subscription->created_at?->format('Y-m-d H:i') }}</div>
</div>
@endsection
