@extends('layouts.admin', ['title' => 'Edit hero slide'])

@section('content')
<div class="header">
    <h1>Edit hero slide</h1>
    <a href="{{ route('admin.hero-slides.index') }}" class="btn btn-muted">Back to list</a>
</div>

<div class="card">
    @if ($slide->imagePublicUrl() !== '')
        <div style="margin-bottom: 14px;">
            <img src="{{ $slide->imagePublicUrl() }}" alt="" style="max-width: 360px; width: 100%; height: auto; border-radius: 8px; border: 1px solid #e5e7eb; display: block;">
        </div>
    @elseif ($slide->image_path)
        <p style="margin-bottom: 14px; font-size: 13px; color: #b45309;">Current image file is missing — upload a new image below.</p>
    @endif

    @can('update', $slide)
    <form method="POST" action="{{ route('admin.hero-slides.update', $slide) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid grid-2">
            <div>
                <label for="title">Title</label>
                <input id="title" name="title" value="{{ old('title', $slide->title) }}" required>
                @error('title') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="sort_order">Sort order</label>
                <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $slide->sort_order) }}">
                @error('sort_order') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4" placeholder="Optional">{{ old('description', $slide->description) }}</textarea>
                @error('description') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="button_label">Button label</label>
                <input id="button_label" name="button_label" value="{{ old('button_label', $slide->button_label) }}" placeholder="e.g. Learn more">
                @error('button_label') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="button_url">Button URL</label>
                <input id="button_url" name="button_url" value="{{ old('button_url', $slide->button_url) }}" placeholder="/our-services or https://…">
                @error('button_url') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="image">Replace image (optional)</label>
                <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp,image/gif">
                @error('image') <div class="error">{{ $message }}</div> @enderror
                <p style="margin-top: 6px; font-size: 12px; color: #64748b;">Leave empty to keep the current image.</p>
            </div>
        </div>
        <div style="margin-top: 14px; display: flex; flex-wrap: wrap; gap: 10px;">
            <button class="btn btn-primary" type="submit">Save changes</button>
            <a href="{{ route('admin.hero-slides.index') }}" class="btn btn-muted">Cancel</a>
        </div>
    </form>
    @else
    <p style="color:#64748b;">You do not have permission to edit this slide.</p>
    @endcan
</div>
@endsection
