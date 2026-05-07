@extends('layouts.admin', ['title' => 'Home Sections'])

@section('content')
<div class="header">
    <h1>Home page sections</h1>
    <div style="display: inline-flex; gap: 8px; flex-wrap: wrap; align-items: center;">
        <a href="{{ route('admin.hero-slides.index') }}" class="btn btn-muted">Hero section</a>
        <a href="{{ route('admin.home-sections.create') }}" class="btn btn-primary">Create</a>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Section</th>
                    <th>Type</th>
                    <th>Variant</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sections as $section)
                <tr>
                    <td>{{ $section['id'] }}</td>
                    <td>{{ $section['title'] }}</td>
                    <td>{{ $section['type'] }}</td>
                    <td>{{ $section['variant'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="margin-top: 10px; color:#64748b; font-size:13px;">
        This is static UI for now. Later we will connect it to the database so sections can be created/reordered.
    </div>
</div>
@endsection

