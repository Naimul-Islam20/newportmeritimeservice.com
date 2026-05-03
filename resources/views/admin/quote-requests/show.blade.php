@extends('layouts.admin', ['title' => 'Quote Request Details'])

@section('content')
<div class="header">
    <h1>Quote Request Details</h1>
    <a href="{{ route('admin.quote-requests.index') }}" class="btn btn-muted">Back</a>
</div>

<div class="card grid">
    <div><strong>Name:</strong> {{ $quoteRequest->name }}</div>
    <div><strong>Designation:</strong> {{ $quoteRequest->designation ?? '-' }}</div>
    <div><strong>Company Name:</strong> {{ $quoteRequest->company_name }}</div>
    <div><strong>Employees:</strong> {{ $quoteRequest->employee_count ?? '-' }}</div>
    <div><strong>Email:</strong> {{ $quoteRequest->email }}</div>
    <div><strong>Mobile:</strong> {{ $quoteRequest->mobile_no }}</div>
    <div><strong>Status:</strong>
        <span class="status {{ $quoteRequest->status === 'new' ? 'status-unread' : 'status-review' }}">
            {{ $quoteRequest->status }}
        </span>
    </div>
    <div><strong>Address:</strong> {{ $quoteRequest->address ?? '-' }}</div>
    <div><strong>Modules Needed:</strong>
        {{ collect($quoteRequest->modules_needed)->map(fn ($item) => str_replace('_', ' ', strtoupper($item)))->implode(', ') }}
    </div>
    <div><strong>Description:</strong></div>
    <div>{{ $quoteRequest->description ?? '-' }}</div>
</div>
@endsection