@extends('layouts.admin', ['title' => 'Add Where We Are location'])

@section('content')
<div class="header">
    <h1>Add location</h1>
    <a class="btn btn-muted" href="{{ route('admin.where-we-are-locations.index') }}">Back to list</a>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.where-we-are-locations.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.where-we-are-locations._form', ['location' => $location])
        <div style="margin-top:14px;">
            <button class="btn btn-primary" type="submit">Create location</button>
        </div>
    </form>
</div>

@include('admin.partials.repeater-rows-script')
@endsection
