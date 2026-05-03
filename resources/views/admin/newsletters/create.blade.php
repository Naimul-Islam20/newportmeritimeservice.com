@extends('layouts.admin', ['title' => 'Create Newsletter'])

@section('content')
<div class="header">
    <h1>Create Newsletter</h1>
    <a href="{{ route('admin.newsletters.index') }}" class="btn btn-muted">Back</a>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.newsletters.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-2">
            <div>
                <label for="title">Title</label>
                <input id="title" name="title" value="{{ old('title') }}" required>
                @error('title') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Select a category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="image">Image</label>
                <input id="image" name="image" type="file" accept="image/*" required>
                <small style="display:block; margin-top:6px; color:#64748b;">Publish date will be added automatically when you create the news.</small>
                @error('image') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div style="margin-top: 16px;">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="8" required>{{ old('description') }}</textarea>
            @error('description') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit" onclick="return confirm('Create this newsletter?')">Save Newsletter</button>
        </div>
    </form>
</div>
@endsection
