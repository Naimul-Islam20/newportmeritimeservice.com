@extends('layouts.admin', ['title' => 'Edit '.$location->hero_title])

@section('content')
<div class="header">
    <h1>Edit: {{ $location->hero_title }}</h1>
    <a class="btn btn-muted" href="{{ url('/where-we-are/'.$location->slug) }}" target="_blank" rel="noopener">View on site</a>
</div>

@if (session('status'))
    <p style="margin:0 0 12px;color:#0d9488;font-size:14px;">{{ session('status') }}</p>
@endif

@php($locationPorts = $location->ports()->orderBy('sort_order')->get())
@if ($locationPorts->isNotEmpty())
<div class="card" style="margin-bottom:16px;">
    <h2 style="margin:0 0 12px;font-size:15px;">ARA ports (sidebar accordion)</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Port</th>
                    <th>URL</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($locationPorts as $p)
                    <tr>
                        <td>{{ $p->title }}</td>
                        <td><code>/where-we-are/{{ $location->slug }}/ports/{{ $p->slug }}</code></td>
                        <td style="text-align:right;">
                            <a class="btn btn-primary" href="{{ route('admin.where-we-are-ports.edit', [$location, $p]) }}">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="card">
    <form method="POST" action="{{ route('admin.where-we-are-locations.update', $location) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.where-we-are-locations._form', ['location' => $location])
        <div style="margin-top:14px;">
            <button class="btn btn-primary" type="submit">Save changes</button>
        </div>
    </form>
</div>

@include('admin.partials.repeater-rows-script')
@endsection
