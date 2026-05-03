@extends('layouts.admin', ['title' => 'Create Newsletter Category'])

@section('content')
<div class="header">
    <h1>Create Newsletter Category</h1>
    <a href="{{ route('admin.newsletter-categories.index') }}" class="btn btn-muted">Back</a>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.newsletter-categories.store') }}">
        @csrf
        <div>
            <label for="name">Category Name</label>
            <input id="name" name="name" value="{{ old('name') }}" required>
            @error('name') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit" onclick="return confirm('Create this category?')">Save Category</button>
        </div>
    </form>
</div>
@endsection
