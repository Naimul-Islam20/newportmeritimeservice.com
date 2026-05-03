@extends('layouts.admin', ['title' => 'Consultation Request Details'])

@section('content')
<div class="header">
    <h1>Consultation Request Details</h1>
    <a href="{{ route('admin.expert-sessions.index') }}" class="btn btn-muted">Back</a>
</div>

<div class="card grid">
    <div><strong>Name:</strong> {{ $expertSession->name }}</div>
    <div><strong>Company Name:</strong> {{ $expertSession->company_name }}</div>
    <div><strong>Designation:</strong> {{ $expertSession->designation }}</div>
    <div><strong>Mobile:</strong> {{ $expertSession->mobile }}</div>
    <div><strong>Email:</strong> {{ $expertSession->email }}</div>
    <div>
        <strong>Status:</strong>
        <span class="status {{ $expertSession->status === 'new' ? 'status-unread' : 'status-review' }}">
            {{ $expertSession->status }}
        </span>
    </div>
</div>
@endsection