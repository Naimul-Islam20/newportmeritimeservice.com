@extends('layouts.admin', ['title' => 'Edit Newsletter'])

@section('content')
<div class="header">
    <h1>Edit Newsletter</h1>
    <a href="{{ route('admin.newsletters.index') }}" class="btn btn-muted">Back</a>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.newsletters.update', $newsletter) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid grid-2">
            <div>
                <label for="title">Title</label>
                <input id="title" name="title" value="{{ old('title', $newsletter->title) }}" required>
                @error('title') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Select a category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $newsletter->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="image">Image</label>
                <input id="image" name="image" type="file" accept="image/*">
                <small style="display:block; margin-top:6px; color:#64748b;">Leave blank to keep current image.</small>
                @error('image') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>

        @if ($newsletter->image_path)
            <div style="margin-top: 16px;">
                <label>Current Image</label>
                <img
                    src="{{ asset($newsletter->image_path) }}"
                    alt="{{ $newsletter->title }}"
                    style="width:140px;height:94px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0;">
            </div>
        @endif

        <div style="margin-top: 16px;">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="8" required>{{ old('description', $newsletter->description) }}</textarea>
            @error('description') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit" onclick="return confirm('Update this newsletter?')">Update Newsletter</button>
        </div>
    </form>
</div>
@endsection
