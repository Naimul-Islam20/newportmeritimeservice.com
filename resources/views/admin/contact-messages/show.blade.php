@extends('layouts.admin', ['title' => 'Contact Form Message'])

@section('content')
<div class="header">
    <h1>Contact message</h1>
    <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-muted">Back</a>
</div>

<div class="card grid">
    <div><strong>Full Name:</strong> {{ $message->full_name }}</div>
    <div><strong>Email:</strong> {{ $message->email }}</div>
    <div><strong>Phone:</strong> {{ $message->phone ?? '—' }}</div>
    <div><strong>Subject:</strong> {{ $message->subject }}</div>
    <div>
        <strong>Status:</strong>
        <span class="status {{ $message->status === 'unread' ? 'status-unread' : 'status-read' }}">
            {{ $message->status }}
        </span>
    </div>
    <div><strong>Message:</strong></div>
    <div>{{ $message->message }}</div>
</div>
@endsection