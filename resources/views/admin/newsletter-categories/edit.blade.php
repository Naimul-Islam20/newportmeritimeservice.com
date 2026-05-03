@extends('layouts.admin', ['title' => 'Edit Newsletter Category'])

@section('content')
<div class="header">
    <h1>Edit Newsletter Category</h1>
    <a href="{{ route('admin.newsletter-categories.index') }}" class="btn btn-muted">Back</a>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.newsletter-categories.update', $category) }}">
        @csrf
        @method('PUT')
        <div>
            <label for="name">Category Name</label>
            <input id="name" name="name" value="{{ old('name', $category->name) }}" required>
            @error('name') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit" onclick="return confirm('Update this category?')">Update Category</button>
        </div>
    </form>
</div>
@endsection
