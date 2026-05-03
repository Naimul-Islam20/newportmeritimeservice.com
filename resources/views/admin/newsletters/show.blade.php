@extends('layouts.admin', ['title' => 'Newsletter Details'])

@section('content')
<div class="header">
    <h1>Newsletter Details</h1>
    <a href="{{ route('admin.newsletters.index') }}" class="btn btn-muted">Back</a>
</div>

<div class="card" style="display:grid; gap:16px;">
    <div class="detail-list">
        <div class="detail-row">
            <div class="detail-label">ID</div>
            <div class="detail-value">{{ $newsletter->id }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Title</div>
            <div class="detail-value">{{ $newsletter->title }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Category</div>
            <div class="detail-value">{{ $newsletter->category?->name ?? '-' }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Date</div>
            <div class="detail-value">{{ $newsletter->published_at?->format('Y-m-d') ?? $newsletter->created_at?->format('Y-m-d') }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Description</div>
            <div class="detail-value">{{ $newsletter->description }}</div>
        </div>
    </div>

    <div>
        <label style="margin-bottom:10px;">Image</label>
        <img
            src="{{ asset($newsletter->image_path) }}"
            alt="{{ $newsletter->title }}"
            style="max-width:100%;width:320px;height:auto;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0;">
    </div>
</div>
@endsection
